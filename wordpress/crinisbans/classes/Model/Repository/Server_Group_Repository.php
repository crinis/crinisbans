<?php
namespace crinis\cb\Model\Repository;

class Server_Group_Repository extends Repository {

	protected static $shortcode = 'cb_server_group_show';

	protected function get_table_update_data( $old, $new ) {
		return [];
	}
}
