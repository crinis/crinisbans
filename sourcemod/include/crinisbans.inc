/**
* Crinisbans: Core include
*
* @author crinis
* @version v0.1.1
* @link https://www.crinis.org
*/

#if defined _crinisbans_included
  #endinput
#endif
#define _crinisbans_included

#define CB_VERSION	"v0.1.1"
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
    MarkNativeAsOptional("CBEscape");
    MarkNativeAsOptional("CBInit");
    MarkNativeAsOptional("CBIsDBConnected");
    MarkNativeAsOptional("CBQuery");
}
#endif

stock bool DisablePlugin(const char[] pluginFile)
{
    char sNewPluginPath[PLATFORM_MAX_PATH + 1], sOldPluginPath[PLATFORM_MAX_PATH + 1];

    BuildPath(Path_SM, sNewPluginPath, sizeof(sNewPluginPath), "plugins/disabled/%s.smx", pluginFile);
    BuildPath(Path_SM, sOldPluginPath, sizeof(sOldPluginPath), "plugins/%s.smx", pluginFile);

    if (!FileExists(sOldPluginPath)) {
        return false;
    }

    if (FileExists(sNewPluginPath)) {
        DeleteFile(sNewPluginPath);
    }

    ServerCommand("sm plugins unload %s", pluginFile);
    RenameFile(sNewPluginPath, sOldPluginPath);
    
    return true;
}

native void CBQuery(SQLQueryCallback callbackFunction, const char[] sQuery, any data = 0, DBPriority priority = DBPrio_Normal);

native void CBConnect();

native bool CBEscape(const char[] sString, char[] sBuffer, int maxlength, int &iWritten = 0);

native void CBInit();

native bool CBIsDBConnected();

native bool CBClientCheck(int iClient);