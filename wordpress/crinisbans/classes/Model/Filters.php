<?php

namespace crinis\cb\Model;

use \crinis\cb\Model\Server_Group;
use \crinis\cb\Model\Server;
use \crinis\cb\Model\Repository\Repository;

class Filters {

	private $server_group_repository;
	private $server_repository;

	public function __construct(
		Repository $server_group_repository,
		Repository $server_repository
	) {
		$this->server_group_repository = $server_group_repository;
		$this->server_repository = $server_repository;
	}

	public function json_serialize( $json_data, $obj ) {
		if ( $obj instanceof Server_Group ) {
			return $this->server_group_json_serialize( $json_data,$obj );
		} elseif ( $obj instanceof Server ) {
			return $this->server_json_serialize( $json_data,$obj );
		}
	}

	public function server_group_json_serialize( $server_group_json_data, $server_group ) {
		$server_group_json_data['serverPostIDs'] = $this->server_repository->get_post_ids_by_server_group( $server_group );
		$server_group_json_data['servers'] = $this->server_repository->get_by_server_group( $server_group );
		return $server_group_json_data;
	}

	public function server_json_serialize( $server_json_data, $server ) {
		$transient_server_data = get_transient( 'cb_server_data_cache_' . $server->get_post_id() );
		if ( $transient_server_data ) {
			$server_json_data = array_merge( $server_json_data, $transient_server_data );
		}
		return $server_json_data;
	}

	public function cron_schedules( $schedules ) {
		if ( ! isset( $schedules['5min'] ) ) {
			$schedules['5min'] = array(
				'interval' => 5 * 60,
				'display' => __( 'Once every 5 minutes' ),
			);
		}
		if ( ! isset( $schedules['30sec'] ) ) {
			$schedules['30sec'] = array(
				'interval' => 30,
				'display' => __( 'Every 30 seconds' ),
			);
		}
		return $schedules;
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
