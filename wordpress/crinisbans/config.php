<?php
return [
		'crinis\cb\Service\RCON_Service' => DI\object()
		->constructorParameter( 'server_player_factory', DI\get( 'crinis\cb\Model\Factory\Server_Player_Factory' ) ),
		'crinis\cb\Model\Ban' => DI\object()
		->method( 'set_reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) ),
		'crinis\cb\Controller\Ajax\Server_Ajax' => DI\object()
		->constructorParameter( 'server_repository', DI\get( 'crinis\cb\Model\Repository\Server_Repository' ) ),
		'crinis\cb\Controller\CPT\Admin_CPT' => DI\object()
		->constructorParameter( 'admin_repository', DI\get( 'crinis\cb\Model\Repository\Admin_Repository' ) )
		->constructorParameter( 'viewhelper', DI\get( 'crinis\cb\View\Viewhelper\CPT_Viewhelper' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) )
		->constructorParameter( 'player_factory', DI\get( 'crinis\cb\Model\Factory\Steam_Player_Factory' ) )
		->constructorParameter( 'group_repository', DI\get( 'crinis\cb\Model\Repository\Group_Repository' ) ),
		'crinis\cb\Controller\CPT\Ban_CPT' => DI\object()
		->constructorParameter( 'ban_repository', DI\get( 'crinis\cb\Model\Repository\Ban_Repository' ) )
		->constructorParameter( 'reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) )
		->constructorParameter( 'player_factory', DI\get( 'crinis\cb\Model\Factory\Steam_Player_Factory' ) ),
		'crinis\cb\Controller\CPT\Group_CPT' => DI\object()
		->constructorParameter( 'group_repository', DI\get( 'crinis\cb\Model\Repository\Group_Repository' ) )
		->constructorParameter( 'viewhelper', DI\get( 'crinis\cb\View\Viewhelper\CPT_Viewhelper' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
		'crinis\cb\Controller\CPT\Reason_CPT' => DI\object()
		->constructorParameter( 'reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) ),
		'crinis\cb\Controller\CPT\Server_CPT' => DI\object()
		->constructorParameter( 'server_repository', DI\get( 'crinis\cb\Model\Repository\Server_Repository' ) )
		->constructorParameter( 'viewhelper', DI\get( 'crinis\cb\View\Viewhelper\CPT_Viewhelper' ) )
		->constructorParameter( 'server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) ),
	    'crinis\cb\Controller\CPT\Server_Group_CPT' => DI\object()
		->constructorParameter( 'server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) ),
	    'crinis\cb\Controller\Shortcodes\Admin_Post_Shortcode' => DI\object()
		->constructorParameter( 'admin_repository', DI\get( 'crinis\cb\Model\Repository\Admin_Repository' ) )
		->constructorParameter( 'group_repository', DI\get( 'crinis\cb\Model\Repository\Group_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Ban_Post_Shortcode' => DI\object()
		->constructorParameter( 'ban_repository', DI\get( 'crinis\cb\Model\Repository\Ban_Repository' ) )
		->constructorParameter( 'reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Group_Post_Shortcode' => DI\object()
		->constructorParameter( 'admin_repository', DI\get( 'crinis\cb\Model\Repository\Admin_Repository' ) )
		->constructorParameter( 'group_repository', DI\get( 'crinis\cb\Model\Repository\Group_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Reason_Post_Shortcode' => DI\object()
		->constructorParameter( 'reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Server_Group_Post_Shortcode' => DI\object()
		->constructorParameter( 'server_repository', DI\get( 'crinis\cb\Model\Repository\Server_Repository' ) )
		->constructorParameter( 'server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Server_Post_Shortcode' => DI\object()
		->constructorParameter( 'server_repository', DI\get( 'crinis\cb\Model\Repository\Server_Repository' ) )
		->constructorParameter( 'server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Ban_Post_List_Shortcode' => DI\object()
		->constructorParameter( 'ban_repository', DI\get( 'crinis\cb\Model\Repository\Ban_Repository' ) )
		->constructorParameter( 'reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Group_Post_List_Shortcode' => DI\object()
		->constructorParameter( 'group_repository', DI\get( 'crinis\cb\Model\Repository\Group_Repository' ) )
		->constructorParameter( 'admin_repository', DI\get( 'crinis\cb\Model\Repository\Admin_Repository' ) )
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
	    'crinis\cb\Controller\Shortcodes\Server_Group_Post_List_Shortcode' => DI\object()
		->constructorParameter( 'server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) ),
	    'crinis\cb\Controller\Actions' => DI\object()
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) )
		->constructorParameter( 'admin_repository', DI\get( 'crinis\cb\Model\Repository\Admin_Repository' ) )
		->constructorParameter( 'server_repository', DI\get( 'crinis\cb\Model\Repository\Server_Repository' ) )
		->constructorParameter( 'reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) ),
	    'crinis\cb\Model\Filters' => DI\object()
		->constructorParameter( 'server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) )
		->constructorParameter( 'server_repository', DI\get( 'crinis\cb\Model\Repository\Server_Repository' ) ),
		'crinis\cb\Model\Factory\I_Factory' => DI\object()
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) )
		->constructorParameter( 'validator', DI\get( 'crinis\cb\Model\Validator' ) ),
		'crinis\cb\Model\Repository\Repository' => DI\object()
		->constructorParameter( 'util', DI\get( 'crinis\cb\Helper\Util' ) ),
		'crinis\cb\Model\Repository\Admin_Repository' => DI\object()
		->constructorParameter( 'db', DI\get( 'crinis\cb\Model\DB\Admins_DB' ) )
		->constructorParameter( 'factory', DI\get( 'crinis\cb\Model\Factory\Admin_Factory' ) )
		->method( 'set_admins_groups_db', DI\get( 'crinis\cb\Model\DB\Admins_Groups_DB' ) )
		->method( 'set_group_repository', DI\get( 'crinis\cb\Model\Repository\Group_Repository' ) ),
		'crinis\cb\Model\Repository\Ban_Repository' => DI\object()
		->constructorParameter( 'db', DI\get( 'crinis\cb\Model\DB\Bans_DB' ) )
		->constructorParameter( 'factory', DI\get( 'crinis\cb\Model\Factory\Ban_Factory' ) )
		->method( 'set_reason_repository', DI\get( 'crinis\cb\Model\Repository\Reason_Repository' ) ),
		'crinis\cb\Model\Repository\Group_Repository' => DI\object()
		->constructorParameter( 'db', DI\get( 'crinis\cb\Model\DB\Groups_DB' ) )
		->constructorParameter( 'factory', DI\get( 'crinis\cb\Model\Factory\Group_Factory' ) ),
		'crinis\cb\Model\Repository\Reason_Repository' => DI\object()
		->constructorParameter( 'db', DI\get( 'crinis\cb\Model\DB\Reasons_DB' ) )
		->constructorParameter( 'factory', DI\get( 'crinis\cb\Model\Factory\Reason_Factory' ) ),
		'crinis\cb\Model\Repository\Server_Group_Repository' => DI\object()
		->constructorParameter( 'db', DI\get( 'crinis\cb\Model\DB\Server_Groups_DB' ) )
		->constructorParameter( 'factory', DI\get( 'crinis\cb\Model\Factory\Server_Group_Factory' ) ),
		'crinis\cb\Model\Repository\Server_Repository' => DI\object()
		->constructorParameter( 'db', DI\get( 'crinis\cb\Model\DB\Servers_DB' ) )
		->constructorParameter( 'factory', DI\get( 'crinis\cb\Model\Factory\Server_Factory' ) )
		->method( 'set_servers_server_groups_db', DI\get( 'crinis\cb\Model\DB\Servers_Server_Groups_DB' ) )
		->method( 'set_server_group_repository', DI\get( 'crinis\cb\Model\Repository\Server_Group_Repository' ) ),
		'crinis\cb\Controller\Settings_Page' => DI\object()
		->constructorParameter( 'options', DI\get( 'crinis\cb\Model\Options' ) )
];
