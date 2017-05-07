<?php
namespace crinis\cb\Model\Repository;

use \crinis\cb\Model\DB\DB;

class Admin_Repository extends Repository {

	private $admins_groups_db;
	private $group_repository;
	protected static $shortcode = 'cb_admin_show';

	public function get_by_user_id( $user_id, $publish = true ) {
		$admins = [];
		$rows = $this->db->get_rows( 'user_id',$user_id );
		if ( $rows ) {
			foreach ( $rows as $row ) {
				$post = get_post( $row['post_id'] );
				if ( ($publish && 'publish' !== $post->post_status) ) {
					continue;
				}
				$admins[] = $this->factory->create( $this->create_init_data( $post,$row ) );
			}
		}
		return $admins;
	}

	protected function get_table_update_data( $old, $new ) {
		$data = [];
		if ( $old->get_steam_ids_64() !== $new->get_steam_ids_64() ) {
			$data['steam_ids_64'] = implode( ',',$new->get_steam_ids_64() );
		}
		if ( $old->get_immunity() !== $new->get_immunity() ) {
			$data['immunity'] = $new->get_immunity();
		}
		if ( $old->get_user_id() !== $new->get_user_id() ) {
			$data['user_id'] = $new->get_user_id();
		}

		$old_flags = $old->get_flags();
		$new_flags = $new->get_flags();

		foreach ( $this->get_all_flags() as $key => $value ) {
			if ( $old_flags[ $key ] !== $new_flags[ $key ] ) {
				$data[ $key ] = $new_flags[ $key ];
			}
		}
		return $data;
	}

	public function get_post_ids_by_group( $group, $publish = true ) {
		$rows = $this->admins_groups_db->get_by_foreign_k( $group->get_post_id() );
		$admin_post_ids = [];
		foreach ( $rows as $row ) {
			if ( ! $publish || 'publish' === get_post_status( $row['local_k'] ) ) {
				$admin_post_ids[] = $row['local_k'];
			}
		}
		return $admin_post_ids;
	}

	public function get_by_group( $group, $publish = true ) {
		return $this->get_by_post_ids( $this->get_post_ids_by_group( $group ),$publish );
	}

	public function update( $new ) {
		parent::update( $new );
		$old = $this->get( $new->get_post_id(), false );

		if ( ! $this->group_repository->post_ids_exist( $new->get_group_post_ids() ) ) {
			return false;
		}
		if ( ! $this->update_intermediate( $this->admins_groups_db,'group_post_ids',$new,$new->get_group_post_ids(),$old->get_group_post_ids() ) ) {
			return false;
		}
		return $new;
	}

	public function set_group_repository( Repository $group_repository ) {
		$this->group_repository = $group_repository;
	}

	public function set_admins_groups_db( DB $admins_groups_db ) {
		$this->admins_groups_db = $admins_groups_db;
	}

	protected function create_init_data( $post, $row ) {
		$init_data  = parent::create_init_data( $post, $row );
		$init_data['group_post_ids'] = [];
		foreach ( $this->admins_groups_db->get_by_local_k( $init_data['row']['post_id'] ) as $row ) {
			$init_data['group_post_ids'][] = $row['foreign_k'];
		}
		return $init_data;
	}

	public function get_all_flags() {
		return $this->db->get_flags();
	}
}
