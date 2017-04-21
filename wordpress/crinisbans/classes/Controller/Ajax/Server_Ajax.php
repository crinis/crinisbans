<?php
namespace crinis\cb\Controller\Ajax;
use \crinis\cb\Model\Repository\Repository;

class Server_Ajax {

	private $server_repository;
	private $name;

	public function __construct( Repository $server_repository ) {
		$this->server_repository = $server_repository;
		$this->name = 'cb_server_ajax';
	}

	public function ajax() {
		if ( empty( $_POST['serverPostIDs'] ) ) {
			exit( wp_json_encode( null ) );
		}

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], $this->get_name() ) ) {
			exit( wp_json_encode( null ) );
		}

		exit( wp_json_encode( $this->server_repository->get_by_post_ids( $_POST['serverPostIDs'] ) ) );
	}

	public function get_name() {
		return $this->name;
	}
}
