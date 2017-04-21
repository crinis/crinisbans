<?php
namespace crinis\cb\Model\Repository;

class Reason_Repository extends Repository {

	protected static $shortcode = 'cb_reason_show';

	protected function get_table_update_data( $old, $new ) {
		$data = [];
		if ( $old->get_duration() !== $new->get_duration() ) {
			$data['duration'] = $new->get_duration();
		}
		return $data;
	}

}
