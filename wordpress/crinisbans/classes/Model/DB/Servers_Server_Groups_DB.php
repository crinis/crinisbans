<?php
namespace crinis\cb\Model\DB;

class Servers_Server_Groups_DB extends Intermediate_DB {

	public function __construct() {
		$this->local_table = 'servers';
		$this->foreign_table = 'server_groups';
		parent::__construct();
	}
}
