<?php
namespace crinis\cb;
class Settings {

	private $options;

	public function __construct() {
		$this->options = get_option( 'cb_settings' );
	}

	public function cb_add_admin_menu() {
		add_options_page( 'Crinisbans', 'Crinisbans', 'manage_options', 'crinisbans', array( $this, 'cb_options_page' ) );
	}

	public function set( $key, $value ) {
		$this->options[ $key ] = sanitize_text_field( $value );
		return update_option( 'cb_settings', $this->options );
	}

	public function get( $key ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : null;
	}

	public function sanitize( $options ) {
		$sanitized_options = [];
		foreach ( $options as $key => $value ) {
			$sanitized_options[ $key ] = sanitize_text_field( $value );
		}
		return $sanitized_options;
	}

	public function cb_settings_init() {

		register_setting( 'pluginPage', 'cb_settings', array( $this, 'sanitize' ) );

		add_settings_section(
			'cb_pluginPage_section',
			__( 'Basic Settings', 'crinisbans' ),
			array( $this,'cb_settings_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'show_admin_perms',
			__( 'Show flags for individual admins', 'crinisbans' ),
			array( $this,'show_admin_perms_render' ),
			'pluginPage',
			'cb_pluginPage_section'
		);

		add_settings_field(
			'version',
			__( 'Version', 'crinisbans' ),
			array( $this,'version_render' ),
			'pluginPage',
			'cb_pluginPage_section'
		);

	}


	public function show_admin_perms_render() {
		?>
		<input type="checkbox" name="cb_settings[show_admin_perms]" <?php checked( ! empty( $this->options['show_admin_perms'] ) ? $this->options['show_admin_perms'] : 0 , 1 ); ?> value="1">
		<?php
	}

	public function version_render() {
		?>
		<input type="text" readonly name="cb_settings[version]" value="<?php echo esc_attr( $this->options['version'] ); ?>">
		<?php
	}

	public function cb_settings_section_callback() {
	}


	public function cb_options_page() {

		?>
		<form action="options.php" method="post">

			<h2>Crinisbans</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

	}
}
?>
