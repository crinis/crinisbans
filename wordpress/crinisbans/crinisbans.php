<?php
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
* Plugin Name: Crinisbans
* Plugin URI: https://crinis.org
* Description: Admin, Ban and Server Manager for Counterstrike: Global Offensive servers
* Version: v0.2.0
* Author: crinis
* Author URI: crinis.org
*/

define( 'CB_PATH', plugin_dir_path( __FILE__ ) );
define( 'CB_URL', WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) );
require 'vendor/autoload.php';


class Crinisbans {

	private $container;
	private $loader;
	private $settings;

	public function __construct() {
		$builder = new DI\ContainerBuilder();
		$builder->addDefinitions( __DIR__ . '/config.php' );
		$this->container = $builder->build();
		$this->loader = $this->container->get( 'crinis\cb\Controller\Loader' );
		$this->settings = $this->container->get( 'crinis\cb\Settings' );
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( self, 'uninstall' ) );
		$this->init();
	}

	public function init() {
		$admin_cpt = $this->container->get( 'crinis\cb\Controller\CPT\Admin_CPT' );
		$group_cpt = $this->container->get( 'crinis\cb\Controller\CPT\Group_CPT' );
		$server_cpt = $this->container->get( 'crinis\cb\Controller\CPT\Server_CPT' );
		$ban_cpt = $this->container->get( 'crinis\cb\Controller\CPT\Ban_CPT' );
		$reason_cpt = $this->container->get( 'crinis\cb\Controller\CPT\Reason_CPT' );
		$server_group_cpt = $this->container->get( 'crinis\cb\Controller\CPT\Server_Group_CPT' );
		$register_cpt = $this->container->get( 'crinis\cb\Controller\Register_CPT' );
		$register_cpt->add( $admin_cpt );
		$register_cpt->add( $group_cpt );
		$register_cpt->add( $server_cpt );
		$register_cpt->add( $reason_cpt );
		$register_cpt->add( $ban_cpt );
		$register_cpt->add( $server_group_cpt );

		$admin_post_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Admin_Post_Shortcode' );
		$ban_post_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Ban_Post_Shortcode' );
		$group_post_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Group_Post_Shortcode' );
		$reason_post_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Reason_Post_Shortcode' );
		$server_group_post_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Server_Group_Post_Shortcode' );
		$server_post_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Server_Post_Shortcode' );
		$ban_post_list_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Ban_Post_List_Shortcode' );
		$group_post_list_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Group_Post_List_Shortcode' );
		$server_group_post_list_shortcode = $this->container->get( 'crinis\cb\Controller\Shortcodes\Server_Group_Post_List_Shortcode' );
		$register_shortcode = $this->container->get( 'crinis\cb\Controller\Register_Shortcode' );
		$register_shortcode->add( $admin_post_shortcode );
		$register_shortcode->add( $ban_post_shortcode );
		$register_shortcode->add( $group_post_shortcode );
		$register_shortcode->add( $reason_post_shortcode );
		$register_shortcode->add( $server_group_post_shortcode );
		$register_shortcode->add( $server_post_shortcode );
		$register_shortcode->add( $ban_post_list_shortcode );
		$register_shortcode->add( $group_post_list_shortcode );
		$register_shortcode->add( $server_group_post_list_shortcode );

		$this->add_actions();
		$this->add_filters();

		$this->loader->run();
	}

	public function add_actions() {

		$this->loader->add_action( 'admin_menu', $this->settings, 'cb_add_admin_menu' , 10, 2 );
		$this->loader->add_action( 'admin_init', $this->settings, 'cb_settings_init' , 10, 2 );

		$actions = $this->container->get( 'crinis\cb\Controller\Actions' );
		$this->loader->add_action( 'profile_update', $actions, 'profile_update' , 10, 2 );
		$this->loader->add_action( 'cb_post_updated', $actions, 'post_updated', 10, 3 );
		$this->loader->add_action( 'cb_post_before_update', $actions, 'post_before_update', 10, 3 );
		$this->loader->add_action( 'cb_cache_server_data', $actions, 'cache_server_data', 10 );

		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_admin_scripts', 10 );
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles', 10 );

		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles', 10 );

		$server_ajax = $this->container->get( 'crinis\cb\Controller\Ajax\Server_Ajax' );
		$this->loader->add_action( 'wp_ajax_' . $server_ajax->get_name(), $server_ajax, 'ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_' . $server_ajax->get_name(), $server_ajax, 'ajax' );
	}

	public function add_filters() {
		$filters = $this->container->get( 'crinis\cb\Model\Filters' );
		$this->loader->add_filter( 'cb_json_serialize', $filters,'json_serialize', 10, 2 );
		$this->loader->add_filter( 'cron_schedules', $filters,'cron_schedules' );
		$this->loader->add_filter( 'comments_open', $filters,'close_ban_comments' );
		$this->loader->add_filter( 'comments_array', $filters,'hide_ban_comments' );
	}

	public function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "activate-plugin_{$plugin}" );

		if ( ! get_role( 'cb_moderator' ) && ! get_role( 'cb_admin' ) ) {
			$wp_admin = get_role( 'administrator' );
			$wp_admin->add_cap( 'cb_admin' );
			$wp_admin->add_cap( 'cb_group' );
			$wp_admin->add_cap( 'cb_server' );
			$wp_admin->add_cap( 'cb_server_group' );
			$wp_admin->add_cap( 'cb_ban' );
			$wp_admin->add_cap( 'cb_reason' );
			$wp_admin->add_cap( 'cb_delete' );

			$moderator = add_role( 'cb_moderator',__( 'Game Moderator' ) );
			$moderator->add_cap( 'cb_ban' );

			$admin = add_role( 'cb_admin',__( 'Game Admin' ) );
			$admin->add_cap( 'cb_admin' );
			$admin->add_cap( 'cb_group' );
			$admin->add_cap( 'cb_server' );
			$admin->add_cap( 'cb_server_group' );
			$admin->add_cap( 'cb_ban' );
			$admin->add_cap( 'cb_reason' );
			$admin->add_cap( 'cb_delete' );
		}

		$cron = $this->container->get( 'crinis\cb\Controller\Cron' );
		$cron->enable_cronjobs();

		$old_version = ltrim( $this->settings->get( 'version' ), 'v' );
		$new_version = ltrim( 'v0.2.0', 'v' );
		if ( version_compare( $old_version, $new_version, '<=' ) ) {
			$this->settings->set( 'version','v0.2.0' );
			return;
		}
		$this->settings->set( 'version','v0.2.0' );
		/*
		Setup all tables.
		*/

		$reasons_db = $this->container->get( 'crinis\cb\Model\DB\Reasons_DB' );
		if ( ! $reasons_db->create_table() ) {
			return false;
		}

		$bans_db = $this->container->get( 'crinis\cb\Model\DB\Bans_DB' );
		if ( ! $bans_db->create_table() ) {
			return false;
		}
		$admins_db = $this->container->get( 'crinis\cb\Model\DB\Admins_DB' );
		if ( ! $admins_db->create_table() ) {
			return false;
		}
		$groups_db = $this->container->get( 'crinis\cb\Model\DB\Groups_DB' );
		if ( ! $groups_db->create_table() ) {
			return false;
		}
		$servers_db = $this->container->get( 'crinis\cb\Model\DB\Servers_DB' );
		if ( ! $servers_db->create_table() ) {
			return false;
		}
		$server_groups_db = $this->container->get( 'crinis\cb\Model\DB\Server_Groups_DB' );
		if ( ! $server_groups_db->create_table() ) {
			return false;
		}
		$admins_groups_db = $this->container->get( 'crinis\cb\Model\DB\Admins_Groups_DB' );
		if ( ! $admins_groups_db->create_table() ) {
			return false;
		}
		$servers_server_groups_db = $this->container->get( 'crinis\cb\Model\DB\Servers_Server_Groups_DB' );
		if ( ! $servers_server_groups_db->create_table() ) {
			return false;
		}

		if ( ! $reasons_db->add_foreign_keys() ) {
			return false;
		}
		if ( ! $bans_db->add_foreign_keys() ) {
			 return false;
		}
		if ( ! $admins_db->add_foreign_keys() ) {
			return false;
		}
		if ( ! $groups_db->add_foreign_keys() ) {
			return false;
		}
		if ( ! $server_groups_db->add_foreign_keys() ) {
			return false;
		}
		if ( ! $servers_db->add_foreign_keys() ) {
			return false;
		}
		if ( ! $admins_groups_db->add_foreign_keys() ) {
			return false;
		}
		if ( ! $servers_server_groups_db->add_foreign_keys() ) {
			return false;
		}
	}

	public function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "deactivate-plugin_{$plugin}" );

		$cron = $this->container->get( 'crinis\cb\Controller\Cron' );
		$cron->disable_cronjobs();
	}

	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		check_admin_referer( 'bulk-plugins' );

	  	if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
			return;
		}

		remove_role( 'cb_moderator' );
		remove_role( 'cb_admin' );
		$wp_admin = get_role( 'administrator' );

		$wp_admin->remove_cap( 'cb_admin' );
		$wp_admin->remove_cap( 'cb_group' );
		$wp_admin->remove_cap( 'cb_server' );
		$wp_admin->remove_cap( 'cb_server_group' );
		$wp_admin->remove_cap( 'cb_ban' );
		$wp_admin->remove_cap( 'cb_reason' );
		$wp_admin->remove_cap( 'cb_delete' );

		do_action( 'cb_drop_tables' );
		delete_option( 'cb_settings' );

		$bans_db = $this->container->get( 'crinis\cb\Model\DB\Bans_DB' );
		$bans_db->drop_table();
		$admins_db = $this->container->get( 'crinis\cb\Model\DB\Admins_DB' );
		$admins_db->drop_table();
		$groups_db = $this->container->get( 'crinis\cb\Model\DB\Groups_DB' );
		$groups->drop_table();
		$servers_db = $this->container->get( 'crinis\cb\Model\DB\Servers_DB' );
		$servers_db->drop_table();
		$server_groups_db = $this->container->get( 'crinis\cb\Model\DB\Server_Groups_DB' );
		$server_groups_db->drop_table();
		$reasons_db = $this->container->get( 'crinis\cb\Model\DB\Reasons_DB' );
		$reasons_db->drop_table();
		$servers_server_groups_db = $this->container->get( 'crinis\cb\Model\DB\Servers_Server_Groups_DB' );
		$servers_server_groups_db->drop_table();
		$admins_groups_db = $this->container->get( 'crinis\cb\Model\DB\Admins_Groups_DB' );
		$admins_groups_db->drop_table();
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'cb-backend', CB_URL . '/dist/backend.bundle.js',[ 'jquery-ui-widget' ] );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'cb-vue', CB_URL . '/dist/styles.css' );
	}

	/*
	Main Javascript is included inside of specific controllers
	*/

}


new Crinisbans();
