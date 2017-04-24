<?php
namespace crinis\cb\Controller;

class Cron {

	public function enable_cronjobs() {
		if ( false === wp_next_scheduled( 'cb_cache_server_data' ) ) {
			wp_schedule_event( time(), '30sec', 'cb_cache_server_data' );
		}
		return wp_next_scheduled( 'cb_cache_server_data' ) ? true:false;
	}

	public function disable_cronjobs() {
		wp_clear_scheduled_hook( 'cb_cache_server_data' );
		return wp_next_scheduled( 'cb_cache_server_data' ) ? false:true;
	}

}
