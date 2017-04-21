<?php
namespace crinis\cb\Controller\Shortcodes;

abstract class Post_Shortcode extends Shortcode {

	public abstract function render();

	public function render_base() {
		if ( is_admin() ) {
			return '';
		}
		do_action( 'cb_post_render_post_list',$this );
		return $this->render();
	}

}
