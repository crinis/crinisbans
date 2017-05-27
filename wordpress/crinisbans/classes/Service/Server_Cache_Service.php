<?php
namespace crinis\cb\Service;

class Server_Cache_Service {

	private $rcon_service;

	public function __construct( RCON_Service $rcon_service ) {
		$this->rcon_service = $rcon_service;
	}

	public function cache_servers( $servers ) {
		foreach ( $servers as $server ) {
			if ( ! $server->has_rcon() ) {
				continue;
			}

			$transient = $this->get_server_cache( $server );

			if ( $this->is_cache_expiring( $transient ) && $this->rcon_service->connect( $server ) ) {
				$server_cache_data = [];
				$server_cache_data['serverInfo'] = $this->rcon_service->get_server_info();
				$server_cache_data['players'] = $this->rcon_service->get_players();
				$server_cache_data['timestamp'] = time();
				set_transient( 'cb_server_cache_' . $server->get_post_id(), $server_cache_data, 0.5 * MINUTE_IN_SECONDS );
			}
		}
	}

	public function get_server_cache( $server ) {
		return get_transient( 'cb_server_cache_' . $server->get_post_id() );
	}

	public function is_cache_expiring( $transient ) {

		if ( empty( $transient ) ) {
			return true;
		}

		$max_time_until_expiration = (0.5 * MINUTE_IN_SECONDS * 1000) / 30;
		if ( ( time() - $transient['timestamp'] ) < $max_time_until_expiration ) {
			return true;
		}

		return false;
	}

}
