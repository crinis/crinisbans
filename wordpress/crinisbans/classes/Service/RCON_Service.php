<?php
namespace crinis\cb\Service;
use \crinis\cb\Model\Domain\Server;
use \crinis\cb\Model\Factory\I_Factory;

class RCON_Service {
	private $server;
	private $condenser_server;
	private $server_player_factory;

	public function __construct( I_Factory $server_player_factory ) {
		$this->server_player_factory = $server_player_factory;
	}

	public function connect( Server $server ) {
		$this->server = $server;
		try {
			$this->condenser_server = new \SourceServer( $this->server->get_address(),$this->server->get_port() );
			$this->condenser_server->initialize();
			if ( $server->has_rcon() ) {
				$this->condenser_server->initSocket();
				$this->condenser_server->rconAuth( $this->server->get_rcon_password() );
			}
		} catch ( \SteamCondenserException $e ) {
			return false;
		}
		return true;
	}

	public function get_server_info() {
		return $this->condenser_server->getServerInfo();
	}

	private function exec_rcon( $command ) {
		try {
			return $this->condenser_server->rconExec( $command );
		} catch ( \SteamCondenserException $e ) {
			return null;
		}
	}

	public function get_players() {
		$server_players = [];
		try {
			foreach ( $this->condenser_server->getPlayers() as $condenser_player ) {
				if ( $condenser_player->isBot() ) {
					continue;
				}
				$data = [];
				$data['condenser_player'] = $condenser_player;
				$player = $this->server_player_factory->create( $data );
				if ( $player ) {
					$server_players[] = $player;
				}
			}
		} catch ( \SteamCondenserException $e ) {
			return null;
		}
		return $server_players;
	}

	public function kick_player( $steam_id_64 ) {
		foreach ( $this->get_players() as $player ) {
			if ( $player->get_steam_id_64() === $steam_id_64 ) {
				$command = sprintf( 'sm_kick "#%s" %s ', $player->get_id(), $reason );
				$response = $this->exec_rcon( $command );
				return (strpos( $response, 'kicked' ) !== false) ? true : false;
			}
		}
		return false;
	}

	public function reload_admins() {
		$response = $this->exec_rcon( 'sm_reloadadmins' );
		return (strpos( $response, 'been refreshed' ) !== false) ? true : false;
	}
}
