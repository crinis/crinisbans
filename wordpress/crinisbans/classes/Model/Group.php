<?php
namespace crinis\cb\Model;

class Group extends Model {

	private $flags;
	private $immunity;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		if ( empty( $data['row'] ) ) {
			$this->flags = [];
			return;
		}
		$this->flags = $this->util->get_array_by_matching_keys( $data['row'],'/^flag_/' );
		$this->immunity = $data['row']['immunity'];
	}

	public function get_immunity() {
		return $this->immunity;
	}

	public function set_immunity( $immunity ) {
		if ( ! isset( $immunity ) || ! $this->validator->is_immunity( $immunity ) ) {
			return false;
		}
		$this->immunity = $immunity;
		return true;
	}

	public function get_flag( $name ) {
		return $this->flags[ $name ];
	}

	public function set_flag( $name, $value ) {
		if ( ! isset( $name ) ) {
			return false;
		}
		$this->flags[ $name ] = $value;
		return true;
	}

	public function set_flags( $flags ) {
		if ( ! is_array( $flags ) ) {
			$flags = [];
		}
		foreach ( $this->flags as $key => $value ) {
			if ( in_array( $key,$flags,true ) ) {
				$this->set_flag( $key,true );
			} else { $this->set_flag( $key,false );
			}
		}
		return true;
	}

	public function get_flags() {
		return $this->flags;
	}
}
