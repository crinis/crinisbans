<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Domain\Reason;

class Reason_Factory extends Factory {

	public function create( $data ) {
		$reason = new Reason( $this->util, $this->validator );
		$reason->set_init_data( $data );
		return $reason;
	}
}
