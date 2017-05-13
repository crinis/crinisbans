<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Server;

class Server_Factory extends Factory {

	public function create( $data ) {
		$server = new Server( $this->util, $this->validator );
		$server->set_init_data( $data );
		return $server;
	}
}
