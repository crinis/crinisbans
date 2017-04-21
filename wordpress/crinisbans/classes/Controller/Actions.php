<?php

namespace crinis\cb\Controller;
use \crinis\cb\Model\Domain\Ban;
use \crinis\cb\Model\Domain\Admin;
use \crinis\cb\Model\Domain\Group;
use \crinis\cb\Helper\Util;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Service\RCON_Service;

class Actions {

	private $util;
	private $admin_repository;
	private $server_repository;
	private $reason_repository;
	private $rcon_service;

	public function __construct(
		Util $util,
		Repository $admin_repository,
		Repository $server_repository,
		Repository $reason_repository,
		RCON_Service $rcon_service
	) {
		$this->util = $util;
		$this->admin_repository = $admin_repository;
		$this->server_repository = $server_repository;
		$this->reason_repository = $reason_repository;
		$this->rcon_service = $rcon_service;
	}

	public function profile_update( $user_id, $old_user_data ) {
		$admins = $this->admin_repository->get_by_user_id( $user_id );
		if ( empty( $admins ) ) {
			return;
		}
		foreach ( $admins as $admin ) {
			$admin->set_title( get_userdata( $admin->get_user_id() )->display_name );
			$this->admin_repository->update( $admin );
		}
	}

	public function post_before_update( $object ) {
		if ( $object instanceof Admin ) {
			$object->set_name( $object->get_steam_id_64() );
			$user = get_userdata( $object->get_user_id() );
			$object->set_title( $user->display_name );
		}
	}

	public function post_updated( $new, $old ) {
		if ( $new instanceof Admin || $new instanceof Group ) {
			$servers = $this->server_repository->get_all();
			foreach ( $servers as $server ) {
				if ( $this->rcon_service->connect( $server ) ) {
					$this->rcon_service->reload_admins();
				}
			}
		} elseif ( $new instanceof Ban ) {
			$this->ban_post_updated( $new, $old );
		}
	}

	public function ban_post_updated( $object ) {
		$reason = $this->reason_repository->get( $object->get_reason_post_id() );
		if ( ! $reason ) {
			return;
		}
		$servers = $this->server_repository->get_all();
		foreach ( $servers as $server ) {
			if ( $this->rcon_service->connect( $server ) ) {
				$this->rcon_service->kick_player( $object->get_steam_id_64(),sprintf( 'You are banned from this server. Reason: %s',$reason->get_title() ) );
			}
		}
	}

	public function cache_server_data() {
		$servers = $this->server_repository->get_all();
		foreach ( $servers as $server ) {
			if ( ! $server->has_rcon() || ! $this->rcon_service->connect( $server ) ) {
				continue;
			}
			$server_data = [];
			$server_data['serverInfo'] = $this->rcon_service->get_server_info();
			$server_data['players'] = $this->rcon_service->get_players();
			set_transient( 'cb_server_data_cache_' . $server->get_post_id(),$server_data, 10 * MINUTE_IN_SECONDS );
		}
		return true;
	}

}

