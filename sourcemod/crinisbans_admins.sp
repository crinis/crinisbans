/**
* Crinisbans: Admins
*
* @author crinis
* @version 0.2.4
* @link https://www.crinis.org
*/
#pragma newdecls required
#pragma semicolon 1

#include <sourcemod>
#include <crinisbans>
#undef REQUIRE_PLUGIN

int sequence = 0;								/** Global unique currentSequence number */
int rebuildCachePart[3] = {0};					/** Cache part currentSequence numbers */
int playerSec[MAXPLAYERS+1];					/** Player-specific currentSequence numbers */
bool playerAuth[MAXPLAYERS+1];				/** Whether a player has been "pre-authed" */


public Plugin myinfo =
{
    name        = "Crinisbans: Admins",
    author      = "crinis",
    description = "Sourcemod plugin to manage your Counterstrike servers in WordPress - Admin Plugin",
    version     = CB_VERSION,
    url         = "https://www.crinis.org"
};

public void OnPluginStart(){

    if (LibraryExists("crinisbans")) 
    {
        CBInit();
    }

}

public void OnConfigsExecuted()
{
    if (DisablePlugin("admin-sql-prefetch") | DisablePlugin("admin-sql-threaded") | DisablePlugin("sql-admin-manager")) 
    {
        DumpAdminCache(AdminCache_Groups, true);
        DumpAdminCache(AdminCache_Admins, true);
    }
}

public bool OnClientConnect(int client, char[] rejectMessage, int maxLength)
{
    playerSec[client] = 0;
    playerAuth[client] = false;
    return true;
}

public void OnClientDisconnect(int client)
{
    playerSec[client] = 0;
    playerAuth[client] = false;
}

public void OnRebuildAdminCache(AdminCachePart part)
{
    /**
     * Mark this part of the cache as being rebuilt.  This is used by the 
     * callback system to determine whether the results should still be 
     * used.
     */
    int currentSequence = ++sequence;
    rebuildCachePart[part] = currentSequence;
    
    if(!CBIsConnected())
    {
        CBConnect();
        return;
    }

    if (part == AdminCache_Groups) 
    {
        FetchGroups(currentSequence);
    } 
    else if (part == AdminCache_Admins) 
    {
        FetchAdmins();
    }
}

public Action OnClientPreAdminCheck(int client)
{
    playerAuth[client] = true;
    
    if (!CBIsConnected())
    {
        return Plugin_Continue;
    }
    /**
     * Similarly, if the cache is in the process of being rebuilt, don't delay 
     * the user's normal connection flow.  The database will soon auth the user 
     * normally.
     */
    if (rebuildCachePart[AdminCache_Admins] != 0)
    {
        return Plugin_Continue;
    }
    
    /**
     * If someone has already assigned an admin ID (bad bad bad), don't 
     * bother waiting.
     */
    if (GetUserAdmin(client) != INVALID_ADMIN_ID)
    {
        return Plugin_Continue;
    }
    
    FetchAdmin(client);
    
    return Plugin_Handled;
}

public void OnReceiveAdmins(Database db, DBResultSet rs, const char[] error, any data)
{
    
    DataPack pk = view_as<DataPack>(data);
    pk.Reset();

    int client = pk.ReadCell();
    
    /**
     * Check if this is the latest result request.
     */
    int currentSequence = pk.ReadCell();
    if (playerSec[client] != currentSequence)
    {
        #if defined _DEBUG
            PrintToServer("Out of sequence");
        #endif

        /* Discard everything, since we're out of currentSequence. */
        delete pk;
        return;
    }
    
    /**
     * If we need to use the results, make sure they succeeded.
     */

    char selectAdminQuery[255];
    pk.ReadString(selectAdminQuery, sizeof(selectAdminQuery));

    if (rs == null)
    {
        LogError("SQL error receiving user: %s", error);
        LogError("Query dump: %s", selectAdminQuery);
        RunAdminCacheChecks(client);
        NotifyPostAdminCheck(client);
        delete pk;
        return;	
    }
    

    int accountCount = rs.RowCount;
    if (accountCount == 0)
    {
        #if defined _DEBUG
            PrintToServer("No admins found");
        #endif
        RunAdminCacheChecks(client);
        NotifyPostAdminCheck(client);
        delete pk;
        return;
    }
    
    char title[128], steamID64[256];
    int postID;
    AdminId adm;
    int postIDIndex, immunityIndex, titleIndex, flagIndex;
    /**
     * Cache user info -- [0] = db id, [1] = cache id, [2] = groups
     */
    int[][] userLookup = new int[accountCount][3];
    int totalUsers = 0;

    pk.ReadString(steamID64, sizeof(steamID64));

    while (rs.FetchRow())
    {

        rs.FieldNameToNum("post_id", postIDIndex);
        postID = rs.FetchInt(postIDIndex);

        rs.FieldNameToNum("title", titleIndex);
        rs.FetchString(titleIndex, title, sizeof(title));

        #if defined _DEBUG
            PrintToServer("Admin found: Title %s, Post ID %d, Steam ID %s", title, postID, steamID64);
        #endif

        /* For dynamic admins we clear anything already in the cache. */
        if ((adm = FindAdminByIdentity("steam", steamID64)) != INVALID_ADMIN_ID)
        {
            RemoveAdmin(adm);
        }
    
        adm = CreateAdmin(title);
        if (!adm.BindIdentity("steam", steamID64))
        {
            LogError("Could not bind prefetched SQL admin (identity \"%s\")", steamID64);
            continue;
        }

        userLookup[totalUsers][0] = postID;
        userLookup[totalUsers][1] = view_as<int>(adm);


        #if defined _DEBUG
            PrintToServer("Found SQL admin (%s, %d)", steamID64, userLookup[totalUsers][2]);
        #endif

        totalUsers++;
    }
    
    /**
     * Try binding the user.
     */	
    
    RunAdminCacheChecks(client);
    adm = GetUserAdmin(client);
    postID = 0;
    
    for (int i=0; i<totalUsers; i++)
    {
        if (userLookup[i][1] == view_as<int>(adm))
        {
            postID = userLookup[i][0];
            break;
        }
    }
    
    #if defined _DEBUG
        PrintToServer("Binding client (%d, %d) resulted in: (%d, %d)", client, sequence, postID, adm);
    #endif
    
    /**
     * If we can't verify that we assigned a database admin, or the user has no 
     * groups, don't bother doing anything.
     */

    if (!postID)
    {
        #if defined _DEBUG
            PrintToServer("Post ID is false");
        #endif
        NotifyPostAdminCheck(client);
        delete pk;
        return;
    }
    

    /**
     * The user has groups -- we need to fetch them!
     */
    char selectGroupquery[1024];
    
    Format(selectGroupquery, sizeof(selectGroupquery), "SELECT posts.post_title AS title FROM {{cb_groups}} AS groups LEFT JOIN {{posts}} AS posts ON posts.ID = groups.post_id LEFT JOIN {{posts}} AS group_posts ON group_posts.ID = groups.post_id LEFT JOIN {{cb_admins_groups}} AS admins_groups ON admins_groups.foreign_k = groups.post_id WHERE admins_groups.local_k = %d AND group_posts.post_status = 'publish'", postID);

    pk.Reset();
    pk.WriteCell(client);
    pk.WriteCell(currentSequence);
    pk.WriteCell(adm);
    pk.WriteString(selectGroupquery);

    CBQuery(OnReceiveAdminGroups, selectGroupquery, pk, DBPrio_Normal);
}

public void OnReceiveAdminGroups(Database db, DBResultSet rs, const char[] error, any data)
{
    DataPack pk = view_as<DataPack>(data);
    pk.Reset();
    
    int client = pk.ReadCell();
    int currentSequence = pk.ReadCell();

    /**
     * Make sure it's the same client.
     */
    if (playerSec[client] != currentSequence)
    {
        delete pk;
        return;
    }

    AdminId adm = view_as<AdminId>(pk.ReadCell());
    
    /**
     * Someone could have sneakily changed the admin id while we waited.
     */
    if (GetUserAdmin(client) != adm)
    {
        NotifyPostAdminCheck(client);
        delete pk;
        return;
    }

    /**
     * See if we got results.
     */
    if (rs == null)
    {
        char query[255];
        pk.ReadString(query, sizeof(query));
        LogError("SQL error receiving user: %s", error);
        LogError("Query dump: %s", query);
        NotifyPostAdminCheck(client);
        delete pk;
        return;
    }
    
    char title[80];
    GroupId grp;
    
    while (rs.FetchRow())
    {

        int titleIndex;

        rs.FieldNameToNum("title", titleIndex);
        rs.FetchString(titleIndex, title, sizeof(title));

        if ((grp = FindAdmGroup(title)) == INVALID_GROUP_ID)
        {
            continue;
        }
        
        #if defined _DEBUG
            PrintToServer("Binding user group (%d, %d, %d, %s, %d)", client, currentSequence, adm, title, grp);
        #endif
        
        adm.InheritGroup(grp);
    }
    
    NotifyPostAdminCheck(client);
    delete pk;
}

public void OnReceiveGroups(Database db, DBResultSet rs, const char[] error, any data)
{
    DataPack pk = view_as<DataPack>(data);
    pk.Reset();
    /**
     * Check if this is the latest result request.
     */
    int currentSequence = pk.ReadCell();
    if (rebuildCachePart[AdminCache_Groups] != currentSequence)
    {
        /* Discard everything, since we're out of currentSequence. */
        delete pk;
        return;
    }
    
    /**
     * If we need to use the results, make sure they succeeded.
     */
    if (rs == null)
    {
        char query[255];
        pk.ReadString(query, sizeof(query));
        LogError("SQL error receiving groups: %s", error);
        LogError("Query dump: %s", query);
        delete pk;
        return;
    }
    
    /**
     * Now start fetching groups.
     */
    char title[128], allFlags[32];
    int immunity;
    while (rs.FetchRow())
    {
        int immunityIndex, titleIndex, flagIndex;


        rs.FieldNameToNum("immunity",immunityIndex);
        immunity = rs.FetchInt(immunityIndex);

        rs.FieldNameToNum("title",titleIndex);
        rs.FetchString(titleIndex,title,sizeof(title));

        #if defined _DEBUG
            PrintToServer("Adding group (%s, %d)", title, immunity);
        #endif
        
        /* Find or create the group */
        GroupId grp;
        if ((grp = FindAdmGroup(title)) == INVALID_GROUP_ID)
        {
            grp = CreateAdmGroup(title);
        }

        allFlags = "zabcdefghijklmnopqrst";

        /* Apply each flag */
        int flagCount = strlen(allFlags);
        AdminFlag flag;
        rs.FieldNameToNum("flag_z", flagIndex);
        for (int i=0; i<flagCount; i++)
        {
            if(rs.FetchInt(flagIndex+i) == 1)
            {
                #if defined _DEBUG
                    PrintToServer("Flag %c enabled", allFlags[i]);
                #endif
                if (!FindFlagByChar(allFlags[i], flag))
                {
                    continue;
                }
                grp.SetFlag(flag, true);
            }
        }
        grp.ImmunityLevel = immunity;
    }
}

public void CB_OnConnect(Database db)
{
    int currentSequence;
    if ((currentSequence = rebuildCachePart[AdminCache_Admins]))
    {
        FetchAdmins();
    }
    if ((currentSequence = rebuildCachePart[AdminCache_Groups]))
    {
        FetchGroups(currentSequence);
    }
}

void FetchAdmin(int client)
{
    if(!CBCheckClient(client))
    {
        return;
    }

    char steamID64[32], escapedSteamID64[64];

    steamID64[0] = '\0';
    if (GetClientAuthId(client, AuthId_SteamID64, steamID64, sizeof(steamID64)))
    {
        if (StrEqual(steamID64, "STEAM_ID_LAN"))
        {
            steamID64[0] = '\0';
        }
    }

    CBEscapeQuery(steamID64, escapedSteamID64, sizeof(escapedSteamID64));

    /**
     * Construct the query using the information the user gave us.
     */
    char query[1024];
    Format(query, sizeof(query), "SELECT admins.*, posts.post_title AS title FROM {{cb_admins}} AS admins LEFT JOIN {{posts}} AS posts ON posts.ID = admins.post_id WHERE find_in_set('%s',admins.steam_ids_64) AND posts.post_status = 'publish'", escapedSteamID64);
    
    /**
     * Send the actual query.
     */	
    playerSec[client] = ++sequence;
    
    DataPack pk = new DataPack();
    pk.WriteCell(client);
    pk.WriteCell(playerSec[client]);
    pk.WriteString(query);
    pk.WriteString(escapedSteamID64);

    CBQuery(OnReceiveAdmins, query, pk, DBPrio_Normal);
}

void FetchAdmins()
{
    for (int i=1; i<=MaxClients; i++)
    {
        if (playerAuth[i] && GetUserAdmin(i) == INVALID_ADMIN_ID)
        {
            FetchAdmin(i);
        }
    }
    
    /**
     * This round of updates is done.  Go in peace.
     */
    rebuildCachePart[AdminCache_Admins] = 0;
}

void FetchGroups(int currentSequence)
{
    char query[255];
    
    Format(query, sizeof(query), "SELECT groups.*, posts.post_title AS title FROM {{cb_groups}} AS groups LEFT JOIN {{posts}} AS posts ON posts.ID = groups.post_id WHERE posts.post_status = 'publish'");

    DataPack pk = new DataPack();
    pk.WriteCell(currentSequence);
    pk.WriteString(query);
    
    CBQuery(OnReceiveGroups, query, pk, DBPrio_Normal);
}

