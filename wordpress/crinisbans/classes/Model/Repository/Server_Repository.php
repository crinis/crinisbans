<?php
namespace crinis\cb\Model\Repository;

use \crinis\cb\Model\DB\DB;

class Server_Repository extends Repository {

	protected static $shortcode = 'cb_server_show';

	private $servers_server_groups_db;
	private $server_group_repository;

	public function get_post_ids_by_server_group( $server_group, $publish = true ) {
		$rows = $this->servers_server_groups_db->get_by_foreign_k( $server_group->get_post_id() );
		$server_post_ids = [];
		foreach ( $rows as $row ) {
			if ( ! $publish || 'publish' === get_post_status( $row['local_k'] ) ) {
				$server_post_ids[] = $row['local_k'];
			}
		}
		return $server_post_ids;
	}

	public function get_by_server_group( $server_group, $published = true ) {
		return $this->get_by_post_ids( $this->get_post_ids_by_server_group( $server_group ),$published );
	}

	protected function get_table_update_data( $old, $new ) {
		$data = [];
		if ( $old->get_address() !== $new->get_address() ) {
			$data['address'] = $new->get_address();
		}
		if ( $old->get_port() !== $new->get_port() ) {
			$data['port'] = $new->get_port();
		}
		if ( $old->get_gotv_address() !== $new->get_gotv_address() ) {
			$data['gotv_address'] = $new->get_gotv_address();
		}
		if ( $old->get_gotv_port() !== $new->get_gotv_port() ) {
			$data['gotv_port'] = $new->get_gotv_port();
		}
		if ( $old->get_rcon_password() !== $new->get_rcon_password() ) {
			$data['rcon_password'] = $this->util->encrypt( $new->get_rcon_password() );
		}
		return $data;
	}

	public function update( $new ) {
		parent::update( $new );
		$old = $this->get( $new->get_post_id() );
		if ( ! $this->server_group_repository->post_ids_exist( $new->get_server_group_post_ids() ) ) {
			return false;
		}
		if ( ! $this->update_intermediate( $this->servers_server_groups_db,'server_group_post_ids',$new,$new->get_server_group_post_ids(),$old->get_server_group_post_ids() ) ) {
			return null;
		}
		return $new;
	}

	public function set_server_group_repository( Repository $server_group_repository ) {
		$this->server_group_repository = $server_group_repository;
	}

	public function set_servers_server_groups_db( DB $servers_server_groups_db ) {
		$this->servers_server_groups_db = $servers_server_groups_db;
	}

	protected function create_init_data( $post, $row ) {
		$init_data  = parent::create_init_data( $post, $row );
		$init_data['server_group_post_ids'] = [];
		foreach ( $this->servers_server_groups_db->get_by_local_k( $init_data['row']['post_id'] ) as $row ) {
			$init_data['server_group_post_ids'][] = $row['foreign_k'];
		}

		return $init_data;
	}

}
