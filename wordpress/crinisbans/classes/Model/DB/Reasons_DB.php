<?php
namespace crinis\cb\Model\DB;

class Reasons_DB extends Post_DB {
	protected function get_table_name() {
		return 'reasons';
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['duration']  = [
			'type' => 'int unsigned NOT NULL DEFAULT 0',
			'format' => '%d',
		];
	}
}
