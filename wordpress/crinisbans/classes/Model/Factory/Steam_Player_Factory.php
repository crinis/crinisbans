<?php
namespace crinis\cb\Model\Factory;

use \crinis\cb\Model\Player\Steam_Player;

class Steam_Player_Factory extends Factory {

	public function create( $data ) {
		$steam_player = new Steam_Player( $this->util, $this->validator );
		$steam_player->set_init_data( $data );
		return $steam_player;
	}
}
