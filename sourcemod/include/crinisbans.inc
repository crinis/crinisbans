/**
* Crinisbans: Core include
*
* @author crinis
* @version v0.2.1
* @link https://www.crinis.org
*/
#pragma newdecls required
#pragma semicolon 1

#if defined _crinisbans_included
  #endinput
#endif
#define _crinisbans_included

#define CB_VERSION	"v0.2.1"
//#define _DEBUG

public SharedPlugin __pl_crinisbans =
{
    name = "crinisbans",
    file = "crinisbans.smx",
    #if defined REQUIRE_PLUGIN
        required = 1
    #else
        required = 0
    #endif
};

#if !defined REQUIRE_PLUGIN
public __pl_crinisbans_SetNTVOptional()
{
    MarkNativeAsOptional("CBConnect");
    MarkNativeAsOptional("CBEscapeQuery");
    MarkNativeAsOptional("CBInit");
    MarkNativeAsOptional("CBIsConnected");
    MarkNativeAsOptional("CBQuery");
}
#endif

stock bool DisablePlugin(const char[] pluginFile)
{
    char newPluginPath[PLATFORM_MAX_PATH + 1], oldPluginPath[PLATFORM_MAX_PATH + 1];

    BuildPath(Path_SM, newPluginPath, sizeof(newPluginPath), "plugins/disabled/%s.smx", pluginFile);
    BuildPath(Path_SM, oldPluginPath, sizeof(oldPluginPath), "plugins/%s.smx", pluginFile);

    if (!FileExists(oldPluginPath)) 
    {
        return false;
    }

    if (FileExists(newPluginPath)) 
    {
        DeleteFile(newPluginPath);
    }

    ServerCommand("sm plugins unload %s", pluginFile);
    RenameFile(newPluginPath, oldPluginPath);
    
    return true;
}

native void CBQuery(SQLQueryCallback callback, const char[] query, any data = 0, DBPriority priority = DBPrio_Normal);

native void CBConnect();

native bool CBEscapeQuery(const char[] query, char[] buffer, int maxLength, int &written = 0);

native void CBInit();

native bool CBIsConnected();

native bool CBCheckClient(int client);