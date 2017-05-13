<?php
namespace crinis\cb\Model;

class Server extends Model {

	private $address;
	private $port;
	private $gotv_address;
	private $gotv_port;
	private $rcon_password;
	private $server_group_post_ids;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		if ( empty( $data['row'] ) ) {
			$this->server_group_post_ids = [];
			return;
		}
		$this->address = $data['row']['address'];
		$this->port = $data['row']['port'];
		$this->gotv_address = $data['row']['gotv_address'];
		$this->gotv_port = $data['row']['gotv_port'];
		$this->rcon_password = $data['row']['rcon_password'];
		$this->server_group_post_ids = empty( $data['server_group_post_ids'] ) ? [] : $data['server_group_post_ids'];
	}

	public function get_address() {
		return $this->address;
	}

	public function set_address( $address ) {
		if ( ! $this->validator->is_server_address( $address ) ) {
			return false;
		}
		$this->address = $address;
		return true;
	}

	public function get_server_group_post_ids() {
		return $this->server_group_post_ids;
	}

	public function add_server_group_post_id( $server_group_post_id ) {
		if ( $this->has_server_group_post_id( $server_group_post_id ) ) {
			return false;
		}
		$this->server_group_post_ids[] = $server_group_post_id;
		return true;
	}

	public function remove_server_group_post_id( $server_group_post_id ) {
		if ( ! $this->has_server_group_post_id( $server_group_post_id ) ) {
			return false;
		}
		$this->server_group_post_ids = array_diff( $this->server_group_post_ids, [ $server_group_post_id ] );
		return true;
	}

	public function has_server_group_post_id( $server_group_post_id ) {
		return in_array( $server_group_post_id, $this->server_group_post_ids, true );
	}

	public function set_server_group_post_ids( $server_group_post_ids ) {
		if ( empty( $server_group_post_ids ) ) {
			$server_group_post_ids = [];
		}

		$removed_server_group_post_ids = array_diff( $this->server_group_post_ids,$server_group_post_ids );
		$added_server_group_post_ids = array_diff( $server_group_post_ids,$this->server_group_post_ids );
		foreach ( $removed_server_group_post_ids as $server_group_post_id ) {
			$this->remove_server_group_post_id( $server_group_post_id );
		}
		foreach ( $added_server_group_post_ids as $server_group_post_id ) {
			$this->add_server_group_post_id( $server_group_post_id );
		}
		return true;
	}

	public function get_address_string() {
		return $this->address . ':' . $this->port;
	}

	public function get_port() {
		return $this->port;
	}

	public function set_port( $port ) {
		if ( ! $this->validator->is_integer( $port ) ) {
			return false;
		}
		$this->port = $port;
		return true;
	}

	public function get_gotv_address() {
		return $this->gotv_address;
	}

	public function set_gotv_address( $gotv_address ) {
		if ( ! $this->validator->is_server_address( $gotv_address ) ) {
			return false;
		}
		$this->gotv_address = $gotv_address;
		return true;
	}

	public function get_gotv_port() {
		return $this->gotv_port;
	}

	public function set_gotv_port( $gotv_port ) {
		if ( ! $this->validator->is_integer( $gotv_port ) ) {
			return false;
		}
		$this->gotv_port = $gotv_port;
		return true;
	}

	public function get_gotv_address_string() {
		if ( ! $this->has_gotv() ) {
			return '';
		}
		return $this->gotv_address . ':' . $this->gotv_port;
	}

	public function get_address_connect() {
		return 'steam://connect/' . $this->get_address_string();
	}

	public function get_gotv_address_connect() {
		if ( ! $this->has_gotv() ) {
			return '';
		}
		return 'steam://connect/' . $this->get_gotv_address_string();
	}

	public function has_gotv() {
		return ( ! empty( $this->gotv_address ) && ! empty( $this->gotv_port ));
	}

	public function get_rcon_password() {
		return $this->util->decrypt( $this->rcon_password );
	}

	public function set_rcon_password( $rcon_password ) {
		if ( ! isset( $rcon_password ) ) {
			return false;
		}
		$this->rcon_password = $this->util->encrypt( $rcon_password );
		return true;
	}

	public function has_rcon() {
		return ! empty( $this->rcon_password ) ? true : false;
	}

	public function jsonSerialize() {
		$server_json_data = parent::jsonSerialize();
		$server_json_data['address'] = $this->get_address();
		$server_json_data['port'] = $this->get_port();
		$server_json_data['GotvAddress'] = $this->get_gotv_address();
		$server_json_data['GotvPort'] = $this->get_gotv_port();
		$server_json_data['hasGotv'] = $this->has_gotv();
		$server_json_data['hasRcon'] = $this->has_rcon();
		$server_json_data['addressConnect'] = $this->get_address_connect();
		$server_json_data['gotvAddressConnect'] = $this->get_gotv_address_connect();
		$server_json_data['serverGroupPostIDs'] = $this->get_server_group_post_ids();
		return $server_json_data;
	}
}
