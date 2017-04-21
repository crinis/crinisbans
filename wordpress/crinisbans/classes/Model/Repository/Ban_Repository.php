<?php
namespace crinis\cb\Model\Repository;

class Ban_Repository extends Repository {

	private $reason_repository;
	protected static $shortcode = 'cb_ban_show';

	protected function get_table_update_data( $old, $new ) {
		$data = [];
		if ( $old->get_steam_id_64() !== $new->get_steam_id_64() ) {
			$data['steam_id_64'] = $new->get_steam_id_64();
		}
		if ( $old->get_reason_post_id() !== $new->get_reason_post_id() ) {
			if ( ! $this->reason_repository->exists( $new->get_reason_post_id() ) ) {
				return false;
			}
			$data['reason_post_id'] = $new->get_reason_post_id();
		}
		return $data;
	}

	public function set_reason_repository( Repository $reason_repository ) {
		$this->reason_repository = $reason_repository;
	}
}
