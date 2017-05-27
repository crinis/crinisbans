<?php
namespace crinis\cb\Controller;
use \crinis\cb\Model\Options;

class Settings_Page {

	private $options;

	public function __construct( Options $options ) {
		$this->options = $options;
	}

	public function add_menu_item() {
		add_options_page( 'Crinisbans', 'Crinisbans', 'manage_options', 'crinisbans', array( $this, 'render_settings_page' ) );
	}

	public function init_page() {

		register_setting( 'pluginPage', 'cb_settings', array( $options, 'sanitize' ) );

		add_settings_section(
			'cb_pluginPage_section',
			__( 'Basic Settings', 'crinisbans' ),
			array( $this,'render_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'render_admin_perms',
			__( 'Show flags for individual admins', 'crinisbans' ),
			array( $this,'render_admin_perms' ),
			'pluginPage',
			'cb_pluginPage_section'
		);

		add_settings_field(
			'render_version',
			__( 'Version', 'crinisbans' ),
			array( $this,'render_version' ),
			'pluginPage',
			'cb_pluginPage_section'
		);

	}


	public function render_admin_perms() {
		require( CB_PATH . 'classes/View/Templates/Settings/Admin_Perms.php' );
	}

	public function render_version() {
		require( CB_PATH . 'classes/View/Templates/Settings/Version.php' );
	}

	public function render_section_callback() {
	}


	public function render_settings_page() {
		require( CB_PATH . 'classes/View/Templates/Settings/Settings_Page.php' );
	}
}

