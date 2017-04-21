<?php
namespace crinis\cb\Model\Repository;

class Group_Repository extends Repository {

	protected static $shortcode = 'cb_group_show';

	protected function get_table_update_data( $old, $new ) {
		$data = [];
		if ( $old->get_immunity() !== $new->get_immunity() ) {
			$data['immunity'] = $new->get_immunity();
		}

		$old_flags = $old->get_flags();
		$new_flags = $new->get_flags();

		foreach ( $this->get_all_flags() as $key => $value ) {
			if ( $old_flags[ $key ] !== $new_flags[ $key ] ) {
				$data[ $key ] = $new_flags[ $key ];
			}
		}
		return $data;
	}

	public function get_all_flags() {
		return $this->db->get_flags();
	}
}
