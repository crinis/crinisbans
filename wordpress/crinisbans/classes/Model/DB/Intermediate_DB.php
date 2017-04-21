<?php
namespace crinis\cb\Model\DB;
abstract class Intermediate_DB extends DB {
	protected $primary_key;
	protected $local_table;
	protected $foreign_table;

	public function __construct() {
		parent::__construct();
		$this->foreign_keys[] = "fk_{$this->table}_{$this->prefix}{$this->local_table}_local_k FOREIGN KEY(local_k) REFERENCES {$this->prefix}{$this->local_table}(post_id) ON DELETE CASCADE ON UPDATE CASCADE";
		$this->foreign_keys[] = "fk_{$this->table}_{$this->prefix}{$this->foreign_table}_foreign_k FOREIGN KEY(foreign_k) REFERENCES {$this->prefix}$this->foreign_table(post_id) ON DELETE CASCADE ON UPDATE CASCADE";
		$this->primary_key = 'id';
	}

	public function get_row_by_id( $id ) {
		$rows = $this->get_rows( 'id',$id );
		return ! empty( $rows ) ? $rows[0] : null;
	}

	public function id_exists( $id ) {
		return $this->exists( 'id',$id );
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['id']  = [
			'type' => 'bigint unsigned AUTO_INCREMENT NOT NULL',
			'format' => '%d',
		];
		$this->columns['local_k']  = [
			'type' => 'bigint unsigned NOT NULL',
			'format' => '%d',
		];
		$this->columns['foreign_k']  = [
			'type' => 'bigint unsigned NOT NULL',
			'format' => '%d',
		];
	}

	public function update_by_id( $id, $data ) {
		global $wpdb;
		foreach ( $data as $key => $value ) {
			if ( ! $value ) {
				$data[ $key ] = null;
			}
		}
		$update = $wpdb->update( $this->table, $data, array(
			'id' => $id,
		),$this->get_data_format( $data ),'%d' );
		if ( $update ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $update;
	}

	public function delete_by_id( $id ) {
		global $wpdb;
		$delete = $wpdb->delete( $this->table, array(
			'id' => $id,
		), array( '%d' ) );
		if ( $delete ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $delete;
	}

	public function get_by_local_k( $local_k ) {
		return $this->get_rows( 'local_k',$local_k );
	}

	public function get_by_foreign_k( $foreign_k ) {
		return $this->get_rows( 'foreign_k',$foreign_k );
	}

	public function delete_by_local_k( $local_k ) {
		global $wpdb;
		$delete = $wpdb->delete( $this->table, array(
			'local_k' => $local_k,
		), array( '%d' ) );
		if ( $delete ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $delete;
	}

	public function delete_by_foreign_k( $foreign_k ) {
		global $wpdb;
		$delete = $wpdb->delete( $this->table, array(
			'foreign_k' => $foreign_k,
		), array( '%d' ) );
		if ( $delete ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $delete;
	}

	public function delete( $local_k, $foreign_k ) {
		global $wpdb;
		$delete = $wpdb->delete( $this->table, array(
			'local_k' => $local_k,
			'foreign_k' => $foreign_k,
		), array( '%d', '%d' ) );
		if ( $delete ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $delete;
	}

	protected function get_table_name() {
		return "{$this->local_table}_{$this->foreign_table}";
	}
}
