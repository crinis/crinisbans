/**
* Crinisbans: Bans
*
* @author crinis
* @version v0.1.1
* @link https://www.crinis.org
*/

#include <sourcemod>
#include <crinisbans>
#undef REQUIRE_PLUGIN

#pragma newdecls required
#pragma semicolon 1

public Plugin myinfo =
{
    name        = "Crinisbans: Bans",
    author      = "crinis",
    description = "Sourcemod plugin to manage your Counterstrike servers in WordPress - Ban Plugin",
    version     = CB_VERSION,
    url         = "https://www.crinis.org"
};

public void OnPluginStart(){

	if (LibraryExists("crinisbans")) {
		CBInit();
	}

}

public void OnConfigsExecuted(){
	DisablePlugin("basebans");
}

public void OnClientPostAdminCheck(int iClient){
	
	if(!CBClientCheck(iClient) || !CBIsDBConnected())
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
	
	char sQuery[1024];
	Format(sQuery, sizeof(sQuery), "SELECT reason_posts.post_title AS sReasonTitle FROM {{cb_bans}} AS bans LEFT JOIN {{posts}} AS posts ON bans.post_id = posts.ID LEFT JOIN {{cb_reasons}} AS reasons ON bans.reason_post_id = reasons.post_id LEFT JOIN {{posts}} AS reason_posts ON reasons.post_id = reason_posts.ID WHERE bans.steam_id_64 = '%s' AND posts.post_status = 'publish' AND DATE_ADD(posts.post_date_gmt,INTERVAL reasons.duration SECOND) > NOW()",sEscapedSteamId64);

	#if defined _DEBUG
		PrintToServer("Checking Ban for iClient %d",iClient);
	#endif

	CBQuery(CheckBan, sQuery, iClient, DBPrio_Normal);
}

public void CheckBan(Database db, DBResultSet results, const char[] error, any iClient)
{
	/*
	We only need one Ban reason here. As we cant show multiple anyway.
	*/
    if (!results.FetchRow()) {
	    #if defined _DEBUG
			PrintToServer("No Ban found in DB",iClient);
		#endif
        return;
    }

    #if defined _DEBUG
		PrintToServer("Ban found in DB for iClient: %d",iClient);
	#endif

	char sReasonTitle[1024];
	int iReasonTitleIndex;

	results.FieldNameToNum("sReasonTitle",iReasonTitleIndex);
	results.FetchString(iReasonTitleIndex,sReasonTitle,sizeof(sReasonTitle));

	DataPack bannedPlayerPack = new DataPack();
	bannedPlayerPack.WriteCell(iClient);
	bannedPlayerPack.WriteString(sReasonTitle);

	CreateTimer(1.5, Timer_KickBannedPlayer, bannedPlayerPack);
}

public Action Timer_KickBannedPlayer(Handle timer, DataPack bannedPlayerPack){
	bannedPlayerPack.Reset();
	int iClient = bannedPlayerPack.ReadCell();
	char sReasonTitle [1024];
	bannedPlayerPack.ReadString(sReasonTitle,sizeof(sReasonTitle));
	KickBannedPlayer(iClient,sReasonTitle);
}

bool KickBannedPlayer(int iClient,const char[] sReasonTitle){
	char auth[20], ban_msg[1024];

	GetClientAuthId(iClient, AuthId_Steam2, auth, sizeof(auth));
	BanIdentity(auth, 1, BANFLAG_AUTHID, "ban_msg", "sm_ban", iClient);

	Format(ban_msg, sizeof(ban_msg), "You are banned from this server! Reason: %s",sReasonTitle);
	KickPlayer(iClient,ban_msg);
}

bool KickPlayer(int iClient,char[] msg){
	// check if client is still connected
    if(!CBClientCheck(iClient))
		return;

	KickClient(iClient,msg);
	#if defined _DEBUG
		PrintToServer("Kicked player: %d",iClient);
	#endif
}