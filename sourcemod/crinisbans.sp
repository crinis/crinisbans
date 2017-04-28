/**
* Crinisbans: Core
*
* @author crinis
* @version v0.2.1
* @link https://www.crinis.org
*/

#pragma newdecls required
#pragma semicolon 1

#include <sourcemod>
#include <crinisbans>
#include <regex>

public Plugin myinfo =
{
    name        = "Crinisbans: Core",
    author      = "crinis",
    description = "Sourcemod plugin to manage your Counterstrike servers in WordPress - Core Plugin",
    version     = CB_VERSION,
    url         = "https://www.crinis.org"
};

Database crinisbansDatabase;

int connectLock = 0;
int sequence = 0;

ConVar tablePrefixCvar;

Handle onConnectForward;

public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int maxErrors)
{
    CreateNative("CBConnect", Native_Connect);
    CreateNative("CBQuery", Native_Query);
    CreateNative("CBEscapeQuery", Native_EscapeQuery);
    CreateNative("CBIsConnected", Native_IsConnected);
    CreateNative("CBInit", Native_Init);
    CreateNative("CBCheckClient", Native_CheckClient);
    
    RegPluginLibrary("crinisbans");

    return APLRes_Success;
}

public void OnPluginStart()
{
    tablePrefixCvar = CreateConVar("cb_table_prefix", "wp_", "Prefix of Wordpress tables");
    AutoExecConfig(true, "crinisbans");
    onConnectForward = CreateGlobalForward("CB_OnConnect", ET_Event, Param_Cell);
}

public void OnMapStart()
{
    CBConnect();
}

public void OnMapEnd()
{
    connectLock = 0;
    delete crinisbansDatabase;
}

public int Native_Init(Handle plugin, int paramCount){
    

}

public int Native_CheckClient(Handle plugin, int paramCount)
{
    int client = GetNativeCell(1);
    if(IsClientInGame(client) && IsClientConnected(client) && !IsFakeClient(client))
    {
        return true;
    } 
    else 
    {
        return false;
    }
}

public int Native_Connect(Handle plugin, int paramCount)
{
    if (connectLock) 
    {
        return;
    }

    connectLock = ++sequence;

    #if defined _DEBUG
        PrintToServer("Trying to connect to database: connectLock=%d",connectLock);
    #endif
    Database.Connect(OnConnect, SQL_CheckConfig("crinisbans") ? "crinisbans" : "default", connectLock);

}

public void OnConnect(Database db, const char[] error, any data)
{

    #if defined _DEBUG
        PrintToServer("OnConnect(%x, %d) connectLock=%d", db, data, connectLock);
    #endif

    /**
        * If this happens to be an old connection request, ignore it.
        */
    if (data != connectLock || crinisbansDatabase != null)
    {
        if(db)
        {
            delete db;
        }
        return;
    }

    connectLock = 0;
    crinisbansDatabase = db;

    if(crinisbansDatabase == null)
    {
        LogError("Failed to connect to database: %s", error);
        return;
    }
    crinisbansDatabase.SetCharset("utf8");
    Call_StartForward(onConnectForward);
    Call_PushCell(crinisbansDatabase);
    Call_Finish();
}

public int Native_IsConnected(Handle plugin, int paramCount)
{
    return !!crinisbansDatabase;
}

public int Native_EscapeQuery(Handle plugin, int paramCount)
{

    int queryLength = GetNativeCell(3);
    if (queryLength <= 0) 
    {
        return false;
    }

    char[] queryToEscape = new char[queryLength];
    char[] escapedQuery = new char[queryLength];

    GetNativeString(1, queryToEscape, queryLength);

    bool success = crinisbansDatabase.Escape(queryToEscape, escapedQuery, queryLength);

    SetNativeString(2, escapedQuery, queryLength);

    return success;
}

public int Native_Query(Handle plugin, int paramCount)
{
    if (!CBIsConnected()) 
    {
        #if defined _DEBUG
            PrintToServer("Not connected to database");
        #endif
        return;
    }

    char query[4096];
    GetNativeString(2, query, sizeof(query));

    Function callback = GetNativeFunction(1);
    any data = GetNativeCell(3);
    DBPriority priority = GetNativeCell(4);

    DataPack pack = new DataPack();
    pack.WriteCell(plugin);
    pack.WriteFunction(callback);
    pack.WriteCell(data);

    SendQuery(QueryCallback, query, pack, priority);
}

void QueryCallback(Database db, DBResultSet results, const char[] error, DataPack pack)
{
    pack.Reset();
    if (error[0]) 
    {
        LogError("Query failed: %s", error);
        return;
    }
    Handle plugin = pack.ReadCell();
    Function callback = pack.ReadFunction();	
    any data = pack.ReadCell();
    delete pack;

    Call_StartFunction(plugin, callback);
    Call_PushCell(db);
    Call_PushCell(results);
    Call_PushString(error);
    Call_PushCell(data);
    Call_Finish();
}


void SendQuery(SQLQueryCallback callback, char query[4096], any data = 0, DBPriority priority = DBPrio_Normal)
{
    char searchString[65], replaceString[65], table[65], tablePrefix[65];

    GetConVarString(tablePrefixCvar, tablePrefix, sizeof(tablePrefix));

    static Regex tableRegex;
    if (!tableRegex) 
    {
        tableRegex = new Regex("\\{\\{([0-9a-zA-Z\\$_]+?)\\}\\}");
    }

    while (tableRegex.Match(query) > 0) 
    {
        tableRegex.GetSubString(0, searchString, sizeof(searchString));
        tableRegex.GetSubString(1, table,  sizeof(table));
        Format(replaceString, sizeof(replaceString), "%s%s", tablePrefix, table);
        ReplaceString(query, sizeof(query), searchString, replaceString);
    }
    
    #if defined _DEBUG
        PrintToServer("Executing query: %s", query);
    #endif

    crinisbansDatabase.Query(callback, query, data, priority);
}
