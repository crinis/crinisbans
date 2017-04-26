/**
* Crinisbans: Admins
*
* @author crinis
* @version v0.2.0
* @link https://www.crinis.org
*/

#include <sourcemod>
#include <crinisbans>
#undef REQUIRE_PLUGIN

#pragma newdecls required
#pragma semicolon 1

int gISequence = 0;								/** Global unique iSequence number */
int gIRebuildCachePart[3] = {0};					/** Cache part iSequence numbers */
int gIPlayerSec[MAXPLAYERS+1];					/** Player-specific iSequence numbers */
bool gIPlayerAuth[MAXPLAYERS+1];				/** Whether a player has been "pre-authed" */


public Plugin myinfo =
{
    name        = "Crinisbans: Admins",
    author      = "crinis",
    description = "Sourcemod plugin to manage your Counterstrike servers in WordPress - Admin Plugin",
    version     = CB_VERSION,
    url         = "https://www.crinis.org"
};

public void OnPluginStart(){

	if (LibraryExists("crinisbans")) {
		CBInit();
	}

}

public void OnConfigsExecuted()
{
    if (DisablePlugin("admin-sql-prefetch") | DisablePlugin("admin-sql-threaded") | DisablePlugin("sql-admin-manager")) {
        // Reload admins
        DumpAdminCache(AdminCache_Groups, true);
        DumpAdminCache(AdminCache_Admins, true);
    }
}

public bool OnClientConnect(int iClient, char[] rejectMsg, int maxLen)
{
	gIPlayerSec[iClient] = 0;
	gIPlayerAuth[iClient] = false;
	return true;
}

public void OnClientDisconnect(int iClient)
{
	gIPlayerSec[iClient] = 0;
	gIPlayerAuth[iClient] = false;
}

public void OnRebuildAdminCache(AdminCachePart part)
{
	/**
	 * Mark this part of the cache as being rebuilt.  This is used by the 
	 * callback system to determine whether the results should still be 
	 * used.
	 */
	int iSequence = ++gISequence;
	gIRebuildCachePart[part] = iSequence;
	
	if(!CBIsDBConnected()){
		CBConnect();
		return;
	}

	if (part == AdminCache_Groups) {
		FetchGroups(iSequence);
	} else if (part == AdminCache_Admins) {
		FetchAdmins();
	}
}

public Action OnClientPreAdminCheck(int iClient)
{
	gIPlayerAuth[iClient] = true;
	
	if (!CBIsDBConnected()) {
        return Plugin_Continue;
    }
	/**
	 * Similarly, if the cache is in the process of being rebuilt, don't delay 
	 * the user's normal connection flow.  The database will soon auth the user 
	 * normally.
	 */
	if (gIRebuildCachePart[AdminCache_Admins] != 0)
	{
		return Plugin_Continue;
	}
	
	/**
	 * If someone has already assigned an admin ID (bad bad bad), don't 
	 * bother waiting.
	 */
	if (GetUserAdmin(iClient) != INVALID_ADMIN_ID)
	{
		return Plugin_Continue;
	}
	
	FetchAdmin(iClient);
	
	return Plugin_Handled;
}

public void OnReceiveAdmins(Database db, DBResultSet rs, const char[] sError, any data)
{
	
	DataPack pk = view_as<DataPack>(data);
	pk.Reset();

	int iClient = pk.ReadCell();
	
	/**
	 * Check if this is the latest result request.
	 */
	int iSequence = pk.ReadCell();
	if (gIPlayerSec[iClient] != iSequence)
	{
		#if defined _DEBUG
			PrintToServer("Out of iSequence");
		#endif

		/* Discard everything, since we're out of iSequence. */
		delete pk;
		return;
	}
	
	/**
	 * If we need to use the results, make sure they succeeded.
	 */

	char sQuerySelectAdmins[255];
	pk.ReadString(sQuerySelectAdmins, sizeof(sQuerySelectAdmins));

	if (rs == null){
		LogError("SQL sError receiving user: %s", sError);
		LogError("Query dump: %s", sQuerySelectAdmins);
		RunAdminCacheChecks(iClient);
		NotifyPostAdminCheck(iClient);
		delete pk;
		return;	
	}
	

	int iNumAccounts = rs.RowCount;
	if (iNumAccounts == 0)
	{
		#if defined _DEBUG
			PrintToServer("No admins found");
		#endif
		RunAdminCacheChecks(iClient);
		NotifyPostAdminCheck(iClient);
		delete pk;
		return;
	}
	
	char sTitle[128], sTotalFlags[32], sSteamId64[256];
	int iImmunity, iPostId;
	AdminId adm;
	int iPostIdIndex, iImmunityIndex, iTitleIndex, iFlagIndex;
	/**
	 * Cache user info -- [0] = db id, [1] = cache id, [2] = groups
	 */
	int[][] userLookup = new int[iNumAccounts][3];
	int iTotalUsers = 0;

	pk.ReadString(sSteamId64, sizeof(sSteamId64));

	while (rs.FetchRow())
	{

		rs.FieldNameToNum("post_id",iPostIdIndex);
		iPostId = rs.FetchInt(iPostIdIndex);

		rs.FieldNameToNum("iImmunity",iImmunityIndex);
		iImmunity = rs.FetchInt(iImmunityIndex);

		rs.FieldNameToNum("title",iTitleIndex);
		rs.FetchString(iTitleIndex,sTitle,sizeof(sTitle));

		#if defined _DEBUG
			PrintToServer("Admin found: Title %s, Post ID %d, Steam ID %s, Admin Immunity %d", sTitle, iPostId, sSteamId64, iImmunity);
		#endif

		/* For dynamic admins we clear anything already in the cache. */
		if ((adm = FindAdminByIdentity("steam", sSteamId64)) != INVALID_ADMIN_ID)
		{
			RemoveAdmin(adm);
		}
	
		adm = CreateAdmin(sTitle);
		if (!adm.BindIdentity("steam", sSteamId64))
		{
			LogError("Could not bind prefetched SQL admin (identity \"%s\")", sSteamId64);
			continue;
		}

		userLookup[iTotalUsers][0] = iPostId;
		userLookup[iTotalUsers][1] = view_as<int>(adm);


		#if defined _DEBUG
			PrintToServer("Found SQL admin (%s, %d)", sSteamId64, userLookup[iTotalUsers][2]);
		#endif

		iTotalUsers++;

		adm.ImmunityLevel = iImmunity;
		
		sTotalFlags = "zabcdefghijklmnopqrst";

		/* Apply each flag */
		int len = strlen(sTotalFlags);
		AdminFlag flag;
		rs.FieldNameToNum("flag_z",iFlagIndex);
		for (int i=0; i<len; i++)
		{
			if(rs.FetchInt(iFlagIndex+i) == 1){
				if (!FindFlagByChar(sTotalFlags[i], flag))
				{
					continue;
				}
				adm.SetFlag(flag, true);
			}
		}
	}
	
	/**
	 * Try binding the user.
	 */	
	
	RunAdminCacheChecks(iClient);
	adm = GetUserAdmin(iClient);
	iPostId = 0;
	
	for (int i=0; i<iTotalUsers; i++)
	{
		if (userLookup[i][1] == view_as<int>(adm))
		{
			iPostId = userLookup[i][0];
			break;
		}
	}
	
	#if defined _DEBUG
		PrintToServer("Binding iClient (%d, %d) resulted in: (%d, %d)", iClient, gISequence, iPostId, adm);
	#endif
	
	/**
	 * If we can't verify that we assigned a database admin, or the user has no 
	 * groups, don't bother doing anything.
	 */

	if (!iPostId)
	{
		#if defined _DEBUG
			PrintToServer("iPostId is false");
		#endif
		NotifyPostAdminCheck(iClient);
		delete pk;
		return;
	}
	

	/**
	 * The user has groups -- we need to fetch them!
	 */
	char sQuerySelectGroups[1024];
	
	Format(sQuerySelectGroups, sizeof(sQuerySelectGroups), "SELECT posts.post_title AS title FROM {{cb_groups}} AS groups LEFT JOIN {{posts}} AS posts ON posts.ID = groups.post_id LEFT JOIN {{posts}} AS group_posts ON group_posts.ID = groups.post_id LEFT JOIN {{cb_admins_groups}} AS admins_groups ON admins_groups.foreign_k = groups.post_id WHERE admins_groups.local_k = %d AND group_posts.post_status = 'publish'", iPostId);

	pk.Reset();
	pk.WriteCell(iClient);
	pk.WriteCell(iSequence);
	pk.WriteCell(adm);
	pk.WriteString(sQuerySelectGroups);

	CBQuery(OnReceiveAdminGroups, sQuerySelectGroups, pk, DBPrio_Normal);
}

public void OnReceiveAdminGroups(Database db, DBResultSet rs, const char[] sError, any data)
{
	#if defined _DEBUG
			PrintToServer("OnReceiveAdminGroups");
	#endif

	DataPack pk = view_as<DataPack>(data);
	pk.Reset();
	
	int iClient = pk.ReadCell();
	int iSequence = pk.ReadCell();

	/**
	 * Make sure it's the same iClient.
	 */
	if (gIPlayerSec[iClient] != iSequence)
	{
		delete pk;
		return;
	}

	AdminId adm = view_as<AdminId>(pk.ReadCell());
	
	/**
	 * Someone could have sneakily changed the admin id while we waited.
	 */
	if (GetUserAdmin(iClient) != adm)
	{
		NotifyPostAdminCheck(iClient);
		delete pk;
		return;
	}

	/**
	 * See if we got results.
	 */
	if (rs == null)
	{
		char sQuery[255];
		pk.ReadString(sQuery, sizeof(sQuery));
		LogError("SQL sError receiving user: %s", sError);
		LogError("Query dump: %s", sQuery);
		NotifyPostAdminCheck(iClient);
		delete pk;
		return;
	}
	
	char sTitle[80];
	GroupId grp;
	
	while (rs.FetchRow())
	{

		int iTitleIndex;

		rs.FieldNameToNum("title",iTitleIndex);
		rs.FetchString(iTitleIndex,sTitle,sizeof(sTitle));

		if ((grp = FindAdmGroup(sTitle)) == INVALID_GROUP_ID)
		{
			continue;
		}
		
		#if defined _DEBUG
				PrintToServer("Binding user group (%d, %d, %d, %s, %d)", iClient, iSequence, adm, sTitle, grp);
		#endif
		
		adm.InheritGroup(grp);
	}
	
	NotifyPostAdminCheck(iClient);
	delete pk;
}

public void OnReceiveGroups(Database db, DBResultSet rs, const char[] sError, any data)
{
	DataPack pk = view_as<DataPack>(data);
	pk.Reset();
	/**
	 * Check if this is the latest result request.
	 */
	int iSequence = pk.ReadCell();
	if (gIRebuildCachePart[AdminCache_Groups] != iSequence)
	{
		/* Discard everything, since we're out of iSequence. */
		delete pk;
		return;
	}
	
	/**
	 * If we need to use the results, make sure they succeeded.
	 */
	if (rs == null)
	{
		char sQuery[255];
		pk.ReadString(sQuery, sizeof(sQuery));
		LogError("SQL sError receiving groups: %s", sError);
		LogError("Query dump: %s", sQuery);
		delete pk;
		return;
	}
	
	/**
	 * Now start fetching groups.
	 */
	char sTitle[128], sTotalFlags[32];
	int iImmunity;
	while (rs.FetchRow())
	{
		int iImmunityIndex, iTitleIndex, iFlagIndex;


		rs.FieldNameToNum("iImmunity",iImmunityIndex);
		iImmunity = rs.FetchInt(iImmunityIndex);

		rs.FieldNameToNum("title",iTitleIndex);
		rs.FetchString(iTitleIndex,sTitle,sizeof(sTitle));

		#if defined _DEBUG
				PrintToServer("Adding group (%s, %d)", sTitle, iImmunity);
		#endif
		
		/* Find or create the group */
		GroupId grp;
		if ((grp = FindAdmGroup(sTitle)) == INVALID_GROUP_ID)
		{
			grp = CreateAdmGroup(sTitle);
		}

		sTotalFlags = "zabcdefghijklmnopqrst";

		/* Apply each flag */
		int len = strlen(sTotalFlags);
		AdminFlag flag;
		rs.FieldNameToNum("flag_z",iFlagIndex);
		for (int i=0; i<len; i++)
		{
			if(rs.FetchInt(iFlagIndex+i) == 1){
				#if defined _DEBUG
					PrintToServer("Flag %c enabled", sTotalFlags[i]);
				#endif
				if (!FindFlagByChar(sTotalFlags[i], flag))
				{
					continue;
				}
				grp.SetFlag(flag, true);
			}
		}
		grp.ImmunityLevel = iImmunity;
	}
}

public void CB_OnConnect(Database db)
{
    int iSequence;
    if ((iSequence = gIRebuildCachePart[AdminCache_Admins])) {
        FetchAdmins();
    }
    if ((iSequence = gIRebuildCachePart[AdminCache_Groups])) {
        FetchGroups(iSequence);
    }
}

void FetchAdmin( int iClient)
{

	if(!CBClientCheck(iClient))
		return;

	char sSteamId64[32], sEscapedSteamId64[64];

	sSteamId64[0] = '\0';
	if (GetClientAuthId(iClient, AuthId_SteamID64, sSteamId64, sizeof(sSteamId64)))
	{
		if (StrEqual(sSteamId64, "STEAM_ID_LAN"))
		{
			sSteamId64[0] = '\0';
		}
	}

	CBEscape(sSteamId64,sEscapedSteamId64,sizeof(sEscapedSteamId64));

	/**
	 * Construct the query using the information the user gave us.
	 */
	char sQuery[1024];
	Format(sQuery, sizeof(sQuery), "SELECT admins.*, posts.post_title AS title FROM {{cb_admins}} AS admins LEFT JOIN {{posts}} AS posts ON posts.ID = admins.post_id WHERE find_in_set('%s',admins.steam_ids_64) AND posts.post_status = 'publish'",sEscapedSteamId64);
	
	/**
	 * Send the actual query.
	 */	
	gIPlayerSec[iClient] = ++gISequence;
	
	DataPack pk = new DataPack();
	pk.WriteCell(iClient);
	pk.WriteCell(gIPlayerSec[iClient]);
	pk.WriteString(sQuery);
	pk.WriteString(sEscapedSteamId64);

	CBQuery(OnReceiveAdmins, sQuery, pk, DBPrio_Normal);
}

void FetchAdmins()
{
	for (int i=1; i<=MaxClients; i++)
	{
		if (gIPlayerAuth[i] && GetUserAdmin(i) == INVALID_ADMIN_ID)
		{
			FetchAdmin(i);
		}
	}
	
	/**
	 * This round of updates is done.  Go in peace.
	 */
	gIRebuildCachePart[AdminCache_Admins] = 0;
}

void FetchGroups(int iSequence)
{
	char sQuery[255];
	
	Format(sQuery, sizeof(sQuery), "SELECT groups.*, posts.post_title AS title FROM {{cb_groups}} AS groups LEFT JOIN {{posts}} AS posts ON posts.ID = groups.post_id WHERE posts.post_status = 'publish'");

	DataPack pk = new DataPack();
	pk.WriteCell(iSequence);
	pk.WriteString(sQuery);
	
	CBQuery(OnReceiveGroups, sQuery, pk, DBPrio_Normal);
}

