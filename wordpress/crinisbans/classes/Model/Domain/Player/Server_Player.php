<?php
namespace crinis\cb\Model\Domain\Player;

class Server_Player extends Player implements \JsonSerializable {

	protected $condenser_player;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		$this->condenser_player = $data['condenser_player'];
		$this->nickname = $this->condenser_player->getName();
		$this->steam_id = $this->condenser_player->getSteamID();
		try {
			$this->steam_id_64 = \SteamId::convertSteamIdToCommunityId( $this->steam_id );
		} catch ( \SteamCondenserException $e ) {
			return false;
		}
		return true;
	}

	public function get_ping() {
		return $this->condenser_player->getPing();
	}

	public function get_connect_time() {
		return $this->condenser_player->getConnectTime();
	}

	public function get_score() {
		return $this->condenser_player->getScore();
	}

	public function get_id() {
		return $this->condenser_player->getRealId();
	}

	public function jsonSerialize() {
		return array(
			 'nickName' => $this->get_nickname(),
			 'steamID' => $this->get_steam_id(),
			 'steamID64' => $this->get_steam_id_64(),
			 'score' => $this->get_score(),
			 'steamProfileUrl' => $this->util->get_url_from_steam_id_64( $this->get_steam_id_64() ),
		);
	}
}
