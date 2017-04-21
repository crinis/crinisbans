<?php
namespace crinis\cb\View\Viewhelper;

class CPT_Viewhelper implements I_Viewhelper {
	public function object_list( $element, $title, $objects, $current_post_ids ) {
		$attrs = [];
		$attrs['element'] = $element;
		$attrs['title'] = $title;
		$attrs['objects'] = $objects;
		$attrs['current_post_ids'] = $current_post_ids;
		require( CB_PATH . 'classes/View/Partials/CPT_Object_List.php' );
	}
}
