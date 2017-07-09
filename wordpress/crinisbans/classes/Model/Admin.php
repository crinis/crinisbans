<?php
namespace crinis\cb\Model;

class Admin extends Model {

	private $user_id;
	private $steam_ids_64;
	private $group_post_ids;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		if ( empty( $data['row'] ) ) {
			$this->steam_ids_64 = [];
			$this->group_post_ids = [];
			return;
		}
		$this->user_id = $data['row']['user_id'];
		$this->steam_ids_64 = empty( $data['row']['steam_ids_64'] ) ? [] : explode( ',',$data['row']['steam_ids_64'] );
		$this->group_post_ids = empty( $data['group_post_ids'] ) ? [] : $data['group_post_ids'];
	}

	public function get_steam_ids_64() {
		return $this->steam_ids_64;
	}

	public function get_steam_id_64() {
		return $this->get_steam_ids_64()[0];
	}

	public function add_steam_id_64( $steam_id_64 ) {
		if ( ! $this->validator->is_steam_id_64( $steam_id_64 ) || $this->has_steam_id_64( $steam_id_64 ) ) {
			return false;
		}
		$this->steam_ids_64[] = $steam_id_64;
		return true;
	}

	public function remove_steam_id_64( $steam_id_64 ) {
		if ( ! $this->has_steam_id_64( $steam_id_64 ) ) {
			return false;
		}
		$this->steam_ids_64 = array_diff( $this->steam_ids_64, [ $steam_id_64 ] );
		return true;
	}

	public function has_steam_id_64( $steam_id_64 ) {
		return in_array( $steam_id_64,$this->steam_ids_64,true );
	}

	public function set_steam_ids_64( $steam_ids_64 ) {
		$removed_steam_ids_64 = array_diff( $this->steam_ids_64,$steam_ids_64 );
		$added_steam_ids_64 = array_diff( $steam_ids_64,$this->steam_ids_64 );
		foreach ( $removed_steam_ids_64 as $steam_id_64 ) {
			$this->remove_steam_id_64( $steam_id_64 );
		}
		foreach ( $added_steam_ids_64 as $steam_id_64 ) {
			$this->add_steam_id_64( $steam_id_64 );
		}
		return true;
	}

	public function get_steam_ids() {
		$steam_ids = [];
		foreach ( $this->get_steam_ids_64() as $steam_id_64 ) {
			$steam_ids[] = $this->util->get_steam_id_from_steam_id_64( $steam_id_64 );
		}
		return $steam_ids;
	}

	public function get_steam_id() {
		return $this->get_steam_ids()[0];
	}

	public function get_user_id() {
		return $this->user_id;
	}

	public function set_user_id( $user_id ) {
		if ( ! $this->validator->is_user_id( $user_id ) ) {
			return false;
		}
		$this->set_title( get_userdata( $user_id )->display_name );
		$this->user_id = $user_id;
		return true;
	}

	public function get_group_post_ids() {
		return $this->group_post_ids;
	}

	public function add_group_post_id( $group_post_id ) {
		if ( $this->has_group_post_id( $group_post_id ) ) {
			return false;
		}
		$this->group_post_ids[] = $group_post_id;
		return true;
	}

	public function remove_group_post_id( $group_post_id ) {
		if ( ! $this->has_group_post_id( $group_post_id ) ) {
			return false;
		}
		$this->group_post_ids = array_diff( $this->group_post_ids, [ $group_post_id ] );
		return true;
	}

	public function has_group_post_id( $group_post_id ) {
		return in_array( $group_post_id,$this->group_post_ids,true );
	}

	public function set_group_post_ids( $group_post_ids ) {
		if ( empty( $group_post_ids ) ) {
			$group_post_ids = [];
		}

		$removed_group_post_ids = array_diff( $this->group_post_ids,$group_post_ids );
		$added_group_post_ids = array_diff( $group_post_ids,$this->group_post_ids );
		foreach ( $removed_group_post_ids as $group_post_id ) {
			$this->remove_group_post_id( $group_post_id );
		}
		foreach ( $added_group_post_ids as $group_post_id ) {
			$this->add_group_post_id( $group_post_id );
		}
		return true;
	}
}
