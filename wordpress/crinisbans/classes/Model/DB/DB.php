<?php
namespace crinis\cb\Model\DB;

abstract class DB {
	protected $table_prefix;
	protected $table;
	protected $charset_collate;
	protected $columns;
	protected $cache_id;

	public function __construct() {
		global $wpdb;
		$this->prefix = $wpdb->prefix . 'cb_';
		$this->table = $this->prefix . $this->get_table_name();
		$this->charset_collate = $wpdb->get_charset_collate();
		$this->columns = [];
		$this->foreign_keys = [];
		$this->add_columns();

		$this->cache_id = wp_cache_get( $this->table );
		if ( false === $this->cache_id ) {
			wp_cache_set( $this->table, 1 );
		}

	}

	protected abstract function get_table_name();

	protected function convert_row_types( $rows ) {
		$converted_rows = [];
		foreach ( $rows as $row ) {
			foreach ( $row as $key => $value ) {
				if ( '%d' === $this->columns[ $key ]['format'] ) {
					$row[ $key ] = intval( $value );
				}
			}
			$converted_rows[] = $row;
		}
		return $converted_rows;
	}

	public function get_rows( $name, $value ) {
		$cached_results = wp_cache_get( "{$this->table}_{$this->cache_id}_{$name}_{$value}", $this->table );
		if ( $cached_results ) {
			return $cached_results;
		} else {
			global $wpdb;
			$select_sql = $wpdb->prepare( "SELECT * FROM {$this->table} WHERE ${name} = {$this->columns[ $name ]['format']}", $value );
			$rows = $wpdb->get_results( $select_sql,'ARRAY_A' );
			wp_cache_set( "{$this->table}_{$this->cache_id}_{$name}_{$value}", $rows,  $this->table );
			return $this->convert_row_types( $rows );
		}
	}

	public function get_row( $name, $value ) {
		$rows = $this->get_rows( $name, $value );
		return $rows ? $rows[0] : null;
	}

	public function get_rows_by_post_ids( $post_ids ) {
		$cached_results = wp_cache_get( "{$this->table}_{$this->cache_id}_{$post_ids}", $this->table );
		if ( $cached_results ) {
			return $cached_results;
		} else {
			global $wpdb;
			$select_sql = $wpdb->prepare( "SELECT * FROM {$this->table} WHERE FIND_IN_SET(post_id,%s)", $post_ids );
			$rows = $wpdb->get_results( $select_sql,'ARRAY_A' );
			wp_cache_set( "{$this->table}_{$this->cache_id}_{$post_ids}", $rows,  $this->table );
			return $this->convert_row_types( $rows );
		}
	}

	public function exists( $name, $value ) {
		return $this->get_rows( $name,$value ) ? true : false;
	}

	public function get_all_rows() {
		$cached_results = wp_cache_get( "{$this->table}_{$this->cache_id}_all", $this->table );
		if ( $cached_results ) {
			return $cached_results;
		} else {
			global $wpdb;
			$select_sql = $wpdb->prepare( "SELECT * FROM {$this->table};", '' );
			$rows = $wpdb->get_results( $select_sql ,'ARRAY_A' );
			wp_cache_set( "{$this->table}_{$this->cache_id}_all", $rows, $this->table );
			return $this->convert_row_types( $rows );
		}
	}

	protected function add_columns() {
	}

	public function insert( $data ) {
		global $wpdb;
		foreach ( $data as $key => $value ) {
			if ( ! $value ) {
				$data[ $key ] = null;
			}
		}
		$insert = $wpdb->insert( $this->table, $data, $this->get_data_format( $data ) );
		if ( $insert ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $insert;
	}

	protected function get_data_format( $data ) {
		$format = [];
		foreach ( $data as $key => $value ) {
			$format[] = $this->columns[ $key ]['format'];
		}
		return $format;
	}

	public function drop_table() {
		global $wpdb;
		$drop_sql = $wpdb->prepare( "DROP TABLE IF EXISTS {$this->table};" );
		$query_result = $wpdb->query( $drop_sql );
		if ( $query_result ) {
			wp_cache_set( $this->table, $this->cache_id + 1 );
		}
		return $query_result;
	}

	public function create_table() {
	    global $wpdb;

		$create_table_sql = "CREATE TABLE $this->table (\n";

		foreach ( $this->columns as $key => $value ) {
			$create_table_sql .= "${key} ${value['type']},\n";
		}

		$create_table_sql .= "PRIMARY KEY ($this->primary_key)";

		$create_table_sql .= ") $this->charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		return dbDelta( $create_table_sql );
	}

	public function add_foreign_keys() {
		global $wpdb;
		foreach ( $this->foreign_keys as $foreign_key ) {
			if ( ! $wpdb->query( "ALTER TABLE $this->table ADD CONSTRAINT $foreign_key" ) ) {
				return false;
			}
		}
		return true;
	}
}
