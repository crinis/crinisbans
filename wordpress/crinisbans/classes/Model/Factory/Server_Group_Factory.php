<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Domain\Server_Group;

class Server_Group_Factory extends Factory {

	public function create( $data ) {
		$server_group = new Server_Group( $this->util, $this->validator );
		$server_group->set_init_data( $data );
		return $server_group;
	}
}
