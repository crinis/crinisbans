<?php
namespace crinis\cb\Controller\Shortcodes;

interface I_Shortcode {
	public static function get_name();
	public function render_base();
}
