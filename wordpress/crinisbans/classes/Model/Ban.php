<?php
namespace crinis\cb\Model;

class Ban extends Model {

	private $steam_id_64;
	private $reason_post_id;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		if ( empty( $data['row'] ) ) {
			return;
		}
		$this->steam_id_64 = $data['row']['steam_id_64'];
		$this->reason_post_id = $data['row']['reason_post_id'];
	}

	public function get_steam_id_64() {
		return $this->steam_id_64;
	}

	public function set_steam_id_64( $steam_id_64 ) {
		if ( empty( $steam_id_64 ) || ! $this->validator->is_steam_id_64( $steam_id_64 ) ) {
			return false;
		}
		$this->set_title( $steam_id_64 );
		$this->set_name( $steam_id_64 );
		$this->steam_id_64 = $steam_id_64;
		return true;
	}

	public function get_steam_id() {
		return $this->util->get_steam_id_from_steam_id_64( $this->steam_id_64 );
	}

	public function get_reason_post_id() {
		return $this->reason_post_id;
	}

	public function set_reason_post_id( $post_id ) {
		if ( empty( $post_id ) ) {
			return false;
		}
		$this->reason_post_id = $post_id;
		return true;
	}

}
