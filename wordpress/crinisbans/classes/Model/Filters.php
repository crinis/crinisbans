<?php

namespace crinis\cb\Model;

use \crinis\cb\Model\Server_Group;
use \crinis\cb\Model\Server;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Service\Server_Cache_Service;

class Filters {

	private $server_group_repository;
	private $server_repository;
	private $server_cache_service;

	public function __construct(
		Repository $server_group_repository,
		Repository $server_repository,
		Server_Cache_Service $server_cache_service
	) {
		$this->server_group_repository = $server_group_repository;
		$this->server_repository = $server_repository;
		$this->server_cache_service = $server_cache_service;
	}

	public function json_serialize( $json, $obj ) {
		if ( $obj instanceof Server_Group ) {
			return $this->server_group_json_serialize( $json, $obj );
		} elseif ( $obj instanceof Server ) {
			return $this->server_json_serialize( $json, $obj );
		}
	}

	public function server_group_json_serialize( $server_group_json, $server_group ) {
		$server_group_json['servers'] = $this->server_repository->get_by_server_group( $server_group );
		return $server_group_json;
	}

	public function server_json_serialize( $server_json, $server ) {
		$server_cache = $this->server_cache_service->get_server_cache( $server );
		if ( $server_cache ) {
			return array_merge( $server_json, $server_cache );
		}
		return $server_json;
	}

	public function close_ban_comments() {
		if ( 'cb-bans' === get_post_type() && ! current_user_can( 'cb_ban' ) ) {
			return false;
		}
		return true;
	}

	public function hide_ban_comments( $comments ) {
		if ( 'cb-bans' === get_post_type() && ! current_user_can( 'cb_ban' ) ) {
			return null;
		}
		return $comments;
	}
}
