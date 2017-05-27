/**
* Crinisbans: Bans
*
* @author crinis
* @version 0.2.2
* @link https://www.crinis.org
*/

#pragma newdecls required
#pragma semicolon 1

#include <sourcemod>
#include <crinisbans>
#undef REQUIRE_PLUGIN

public Plugin myinfo =
{
    name        = "Crinisbans: Bans",
    author      = "crinis",
    description = "Sourcemod plugin to manage your Counterstrike servers in WordPress - Ban Plugin",
    version     = CB_VERSION,
    url         = "https://www.crinis.org"
};

public void OnPluginStart()
{
    if (LibraryExists("crinisbans")) {
        CBInit();
    }
}

public void OnConfigsExecuted()
{
    DisablePlugin("basebans");
}

public void OnClientPostAdminCheck(int client)
{
    
    if(!CBCheckClient(client) || !CBIsConnected())
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
    
    char query[1024];
    Format(query, sizeof(query), "SELECT reason_posts.post_title AS reason_title FROM {{cb_bans}} AS bans LEFT JOIN {{posts}} AS posts ON bans.post_id = posts.ID LEFT JOIN {{cb_reasons}} AS reasons ON bans.reason_post_id = reasons.post_id LEFT JOIN {{posts}} AS reason_posts ON reasons.post_id = reason_posts.ID WHERE bans.steam_id_64 = '%s' AND posts.post_status = 'publish' AND DATE_ADD(posts.post_date_gmt,INTERVAL reasons.duration SECOND) > NOW()", escapedSteamID64);

    #if defined _DEBUG
        PrintToServer("Checking Ban for client %d", client);
    #endif

    CBQuery(CheckBan, query, client, DBPrio_Normal);
}

public void CheckBan(Database db, DBResultSet results, const char[] error, any client)
{
    /*
    * We only need one Ban reason here. As we cant show multiple anyway.
    */
    if (!results.FetchRow()) 
    {
        #if defined _DEBUG
            PrintToServer("No Ban found for client %d", client);
        #endif
        return;
    }

    #if defined _DEBUG
        PrintToServer("Ban found for client %d", client);
    #endif

    char reasonTitle[1024];
    int reasonTitleIndex;

    results.FieldNameToNum("reason_title", reasonTitleIndex);
    results.FetchString(reasonTitleIndex, reasonTitle, sizeof(reasonTitle));

    DataPack bannedPlayerPack = new DataPack();
    bannedPlayerPack.WriteCell(client);
    bannedPlayerPack.WriteString(reasonTitle);

    CreateTimer(1.5, Timer_KickBannedPlayer, bannedPlayerPack);
}

public Action Timer_KickBannedPlayer(Handle timer, DataPack bannedPlayerPack)
{
    bannedPlayerPack.Reset();
    int client = bannedPlayerPack.ReadCell();
    char reasonTitle [1024];
    bannedPlayerPack.ReadString(reasonTitle, sizeof(reasonTitle));
    KickBannedPlayer(client, reasonTitle);
}

bool KickBannedPlayer(int client, const char[] reasonTitle)
{
    char auth[20], ban_message[1024];
    Format(ban_message, sizeof(ban_message), "You are banned from this server! Reason: %s", reasonTitle);

    GetClientAuthId(client, AuthId_Steam2, auth, sizeof(auth));
    BanIdentity(auth, 1, BANFLAG_AUTHID, ban_message, "sm_ban", client);

    KickPlayer(client, ban_message);
}

bool KickPlayer(int client, char[] kickMessage)
{
    if(!CBCheckClient(client))
    {
        return;
    }

    KickClient(client, "%s", kickMessage);
    #if defined _DEBUG
        PrintToServer("Kicked player: %d", client);
    #endif
}