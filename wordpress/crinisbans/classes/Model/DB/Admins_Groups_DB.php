<?php
namespace crinis\cb\Model\DB;

class Admins_Groups_DB extends Intermediate_DB {

	public function __construct() {
		$this->local_table = 'admins';
		$this->foreign_table = 'groups';
		parent::__construct();
	}
}
