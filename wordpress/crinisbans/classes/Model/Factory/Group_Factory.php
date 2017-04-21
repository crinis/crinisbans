<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Domain\Group;

class Group_Factory extends Factory {

	public function create( $data ) {
		$group = new Group( $this->util, $this->validator );
		$group->set_init_data( $data );
		return $group;
	}
}
