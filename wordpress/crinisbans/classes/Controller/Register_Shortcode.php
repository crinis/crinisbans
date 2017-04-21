<?php
namespace crinis\cb\Controller;
use \crinis\cb\Controller\Shortcodes\I_Shortcode;

class Register_Shortcode {
	public function add( I_Shortcode $shortcode ) {
		add_shortcode( $shortcode::get_name(), array( $shortcode, 'render_base' ) );
	}
}
