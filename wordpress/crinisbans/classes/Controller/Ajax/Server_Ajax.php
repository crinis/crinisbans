<?php
namespace crinis\cb\Controller\Ajax;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Service\Server_Cache_Service;

class Server_Ajax {

	private $server_repository;
	private $server_cache_service;
	private $name;

	public function __construct( Repository $server_repository, Server_Cache_Service $server_cache_service ) {
		$this->server_repository = $server_repository;
		$this->server_cache_service = $server_cache_service;
		$this->name = 'cb_server_ajax';
	}

	public function ajax() {
		if ( empty( $_POST['serverPostIDs'] ) ) {
			exit( wp_json_encode( null ) );
		}

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], $this->get_name() ) ) {
			exit( wp_json_encode( null ) );
		}

		$servers = $this->server_repository->get_by_post_ids( $_POST['serverPostIDs'] );
		$this->server_cache_service->cache_servers( $servers );

		exit( wp_json_encode( $servers ) );
	}

	public function get_name() {
		return $this->name;
	}
}
