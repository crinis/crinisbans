<?php
namespace crinis\cb\Model\DB;
abstract class Post_DB extends DB {
	protected $primary_key;

	public function __construct() {
		parent::__construct();
		$this->primary_key = 'post_id';
		global $wpdb;
		$this->foreign_keys[] = "fk_{$this->table}_{$wpdb->prefix}posts_post_id FOREIGN KEY(post_id) REFERENCES {$wpdb->prefix}posts(ID) ON DELETE CASCADE ON UPDATE CASCADE";
	}

	public function get_row_by_post_id( $post_id ) {
		$rows = $this->get_rows( 'post_id',$post_id );
		return ! empty( $rows ) ? $rows[0] : null;
	}

	public function post_id_exists( $post_id ) {
		return $this->exists( 'post_id',$post_id );
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['post_id']  = [
			'type' => 'bigint unsigned NOT NULL',
			'format' => '%d',
		];
	}

	public function update_by_post_id( $post_id, $data ) {
		if ( empty( $data ) ) {
			return true;
		}
		foreach ( $data as $key => $value ) {
			if ( ! $value ) {
				$data[ $key ] = null;
			}
		}

		global $wpdb;
		$update = $wpdb->update( $this->table, $data, array(
			'post_id' => $post_id,
		),$this->get_data_format( $data ),'%d' );
		$success = $update !== false ? true : false;
		if ( $success ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $success;
	}

	public function delete_by_post_id( $post_id ) {
		global $wpdb;
		$delete = $wpdb->delete( $this->table, array(
			'post_id' => $post_id,
		), array( '%d' ) );
		if ( $delete ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $delete;
	}
}
