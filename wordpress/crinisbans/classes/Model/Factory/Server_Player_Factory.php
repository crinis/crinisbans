<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Domain\Player\Server_Player;

class Server_Player_Factory extends Factory {

	public function create( $data ) {
		$server_player = new Server_Player( $this->util, $this->validator );
		$server_player->set_init_data( $data );
		return $server_player;
	}
}
