<?php
namespace crinis\cb\Model;

class Options {

	private $options;

	public function __construct() {
		$this->options = get_option( 'cb_settings' );
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

}
