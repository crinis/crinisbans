# Crinisbans

This repository includes a WordPress and a Sourcemod plugin. It integrates a basic user interface to manage admins and bans on Counterstrike: Global Offensive servers into WordPress. It also includes shortcodes to list servers, bans and admins. It is intended to be used for the [crinis.org](https://crinis.org) servers, but you are free to use it.
It is in development, so don't use it if you are afraid of trouble.

## Install

### Wordpress Installation

Add the WordPress plugin to your Wordpress plugins folder. Activate the plugin in WordPress. After activating the plugin you should see multiple custom post types.

### Sourcemod Installation

Install the Sourcemod plugin as usual. Configure the crinisbans.cfg file inside your cfg folder to your needs.

## Get started

### Roles
This plugin adds a couple of roles and capabilities with permissions that are required to start working with the plugin for non-admin users. **This plugin requires you to use a plugin like [Wordpress Role Editor](https://wordpress.org/plugins/user-role-editor/).** You can decide to add a user to the predefined Roles "Game Admin" or "Game Moderator" or you can add your own roles and grant specific capabilities.

### Shortcodes
Add the following shortcodes on any WordPress page you want. These will give you an admin list, ban list or a server list.
```
[cb_group_list_sc]
[cb_ban_list_sc]
[cb_server_group_list_sc]
```
## License

See LICENSE.md (GPLv3).