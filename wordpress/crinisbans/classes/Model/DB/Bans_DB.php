<?php
namespace crinis\cb\Model\DB;

class Bans_DB extends Post_DB {

	public function __construct() {
		parent::__construct();
		$this->foreign_keys[] = "fk_{$this->table}_{$this->prefix}reasons_reason_post_id FOREIGN KEY(reason_post_id) REFERENCES {$this->prefix}reasons(post_id) ON DELETE SET NULL ON UPDATE CASCADE";
	}

	protected function get_table_name() {
		return 'bans';
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['steam_id_64']  = [
			'type' => 'varchar(255) NOT NULL',
			'format' => '%s',
		];
		  $this->columns['reason_post_id'] = [
			  'type' => 'bigint unsigned DEFAULT NULL',
			  'format' => '%d',
		  ];
	}
}
