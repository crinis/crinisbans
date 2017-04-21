<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Domain\Ban;

class Ban_Factory extends Factory {

	public function create( $data ) {
		$ban = new Ban( $this->util, $this->validator );
		$ban->set_init_data( $data );
		return $ban;
	}
}
