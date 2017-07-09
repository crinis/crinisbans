<?php
namespace crinis\cb\Model\DB;

class Admins_DB extends DB {

	public function __construct() {
		parent::__construct();
		global $wpdb;
		$this->foreign_keys[] = "fk_{$this->table}_{$wpdb->prefix}users_user_id FOREIGN KEY(user_id) REFERENCES {$wpdb->prefix}users(ID) ON DELETE SET NULL ON UPDATE CASCADE";
	}

	protected function get_table_name() {
		return 'admins';
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['user_id']  = [
			'type' => 'bigint unsigned DEFAULT NULL',
			'format' => '%d',
		];
		$this->columns['steam_ids_64']  = [
			'type' => 'varchar(255) DEFAULT NULL',
			'format' => '%s',
		];
	}

}
