/**
* Crinisbans: Core
*
* @author crinis
* @version v0.1.0
* @link https://www.crinis.org
*/

#include <sourcemod>
#include <crinisbans>
#include <regex>
#pragma newdecls required
#pragma semicolon 1

public Plugin myinfo =
{
    name        = "Crinisbans: Core",
    author      = "crinis",
    description = "Sourcemod plugin to manage your Counterstrike servers in WordPress - Core Plugin",
    version     = CB_VERSION,
    url         = "https://www.crinis.org"
};

Database gCbDb;

int gIConnectLock = 0;
int gISequence = 0;

ConVar gWPTablePrefix;

Handle gCbOnConnect;

public APLRes AskPluginLoad2(Handle myself, bool bLate, char[] sError, int iErrMax)
{
	CreateNative("CBConnect",		Native_DBConnect);
	CreateNative("CBQuery", 		Native_DBQuery);
	CreateNative("CBEscape",		Native_DBEscape);
	CreateNative("CBIsDBConnected", Native_IsDBConnected);
	CreateNative("CBInit", 			Native_Init);
	CreateNative("CBClientCheck", 	Native_ClientCheck);
	
	RegPluginLibrary("crinisbans");

	return APLRes_Success;
}

public void OnPluginStart(){
	gWPTablePrefix = CreateConVar("cb_table_prefix", "wp_", "Prefix of Wordpress tables");
	AutoExecConfig(true, "crinisbans");
	gCbOnConnect = CreateGlobalForward("CB_OnConnect", ET_Event, Param_Cell);
}

public void OnMapStart()
{
    CBConnect();
}

public void OnMapEnd()
{
    gIConnectLock = 0;
    delete gCbDb;
}

public int Native_Init(Handle plugin, int iNumParams){
	

}

public int Native_ClientCheck(Handle plugin, int iNumParams){
	int iClient = GetNativeCell(1);
	if(IsClientInGame(iClient) && IsClientConnected(iClient) && !IsFakeClient(iClient))
		return true;
	else
		return false;
}

public int Native_DBConnect(Handle plugin, int iNumParams){
	if (gIConnectLock) {
		return;
	}
	gIConnectLock = ++gISequence;

	#if defined _DEBUG
		PrintToServer("Trying to connect to database: gIConnectLock=%d",gIConnectLock);
	#endif
	Database.Connect(OnDBConnect, SQL_CheckConfig("crinisbans") ? "crinisbans" : "default", gIConnectLock);

}

public void OnDBConnect(Database db, const char[] sError, any data){

	#if defined _DEBUG
		PrintToServer("OnDBConnect(%x, %d) gIConnectLock=%d", db, data, gIConnectLock);
	#endif

	/**
	 * If this happens to be an old connection request, ignore it.
	 */
	if (data != gIConnectLock || gCbDb != null)
	{
		if(db){
			delete db;
		}
		return;
	}

	gIConnectLock = 0;
	gCbDb = db;

	if(gCbDb == null){
		LogError("Failed to connect to database: %s", sError);
		return;
	}
	gCbDb.SetCharset("utf8");
	Call_StartForward(gCbOnConnect);
    Call_PushCell(gCbDb);
	Call_Finish();
}

public int Native_IsDBConnected(Handle plugin, int iNumParams)
{
	return !!gCbDb;
}

public int Native_DBEscape(Handle plugin, int iNumParams)
{

    int iLength = GetNativeCell(3);
    if (iLength <= 0) {
        return false;
    }

    char[] sToEscape = new char[iLength];
	char[] sEscaped = new char[iLength];

    GetNativeString(1, sToEscape, iLength);

    bool success = gCbDb.Escape(sToEscape, sEscaped, iLength);

    SetNativeString(2, sEscaped, iLength);

    return success;
}

public int Native_DBQuery(Handle smPlugin, int iNumParams)
{
    if (!CBIsDBConnected()) {
    	#if defined _DEBUG
			PrintToServer("DB is not connected");
		#endif
        return;
    }

    char sQuery[4096];
    GetNativeString(2, sQuery, sizeof(sQuery));

    Function callbackFunction = GetNativeFunction(1);
    any data = GetNativeCell(3);
    DBPriority priority = GetNativeCell(4);

    DataPack hPack = new DataPack();
    hPack.WriteCell(smPlugin);
    hPack.WriteFunction(callbackFunction);
    hPack.WriteCell(data);

    DBExecuteQuery(DBQueryCallback, sQuery, hPack, priority);
}

void DBQueryCallback(Database db, DBResultSet results, const char[] sError, DataPack pack){
	pack.Reset();
	if (sError[0]) {
        LogError("Query failed: %s", sError);
        return;
    }
	Handle smPlugin = pack.ReadCell();
	Function callbackFunction = pack.ReadFunction();	
	any data = pack.ReadCell();
	delete pack;

	Call_StartFunction(smPlugin, callbackFunction);
	Call_PushCell(db);
	Call_PushCell(results);
	Call_PushString(sError);
	Call_PushCell(data);
	Call_Finish();
}


void DBExecuteQuery(SQLQueryCallback callbackFunction, char sQuery[4096], any data = 0, DBPriority priority = DBPrio_Normal){


    // Format {{table}} as DatabasePrefixtable
    char sSearch[65], sReplace[65], sTable[65], sWPTablePrefix[65];

    GetConVarString(gWPTablePrefix, sWPTablePrefix, sizeof(sWPTablePrefix));

    static Regex tableRegex;
    if (!tableRegex) {
        tableRegex = new Regex("\\{\\{([0-9a-zA-Z\\$_]+?)\\}\\}");
    }

    while (tableRegex.Match(sQuery) > 0) {
        tableRegex.GetSubString(0, sSearch, sizeof(sSearch));
        tableRegex.GetSubString(1, sTable,  sizeof(sTable));
        Format(sReplace, sizeof(sReplace), "%s%s", sWPTablePrefix, sTable);
        ReplaceString(sQuery, sizeof(sQuery), sSearch, sReplace);
	}
	
	#if defined _DEBUG
		PrintToServer("Executing query: %s", sQuery);
	#endif

	gCbDb.Query(callbackFunction, sQuery, data, priority);
}
