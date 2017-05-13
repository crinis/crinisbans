<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Admin;

class Admin_Factory extends Factory {

	public function create( $data ) {
		$admin = new Admin( $this->util, $this->validator );
		$admin->set_init_data( $data );
		return $admin;
	}
}
