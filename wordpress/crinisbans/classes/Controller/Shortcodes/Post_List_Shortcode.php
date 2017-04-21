<?php
namespace crinis\cb\Controller\Shortcodes;

abstract class Post_List_Shortcode extends Shortcode {

	public abstract function render();

	public function render_base() {
		if ( is_admin() ) {
			return '';
		}
		do_action( 'cb_pre_render_post_list',$this );
		return $this->render();
	}

}
