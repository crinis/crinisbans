<?php
namespace crinis\cb\Model\DB;

class Servers_DB extends Post_DB {
	protected function get_table_name() {
		return 'servers';
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['address']  = [
			'type' => 'varchar(255) NOT NULL',
			'format' => '%s',
		];
		$this->columns['port']  = [
			'type' => 'int unsigned NOT NULL',
			'format' => '%d',
		];
		$this->columns['gotv_address']  = [
			'type' => 'varchar(255) DEFAULT NULL',
			'format' => '%s',
		];
		$this->columns['gotv_port']  = [
			'type' => 'int unsigned DEFAULT NULL',
			'format' => '%d',
		];
		$this->columns['rcon_password']  = [
			'type' => 'varchar(255) DEFAULT NULL',
			'format' => '%s',
		];
	}
}
