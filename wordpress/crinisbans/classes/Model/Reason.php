<?php
namespace crinis\cb\Model;

class Reason extends Model {

	private $duration;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		if ( empty( $data['row'] ) ) {
			return;
		}
		$this->duration = $data['row']['duration'];
	}

	public function get_duration() {
		return $this->duration;
	}

	public function set_duration( $duration ) {
		if ( ! isset( $duration ) || ! $this->validator->is_integer( $duration ) ) {
			return false;
		}
		$this->duration = $duration;
		return true;
	}

}
