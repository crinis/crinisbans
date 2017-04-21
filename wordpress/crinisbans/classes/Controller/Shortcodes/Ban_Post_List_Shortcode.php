<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Ban_Post_List_Shortcode extends Post_List_Shortcode {

	protected static $name = 'cb_ban_list_sc';

	private $ban_repository;
	private $reason_repository;
	private $util;

	public function __construct( Repository $ban_repository, Repository $reason_repository, Util $util ) {
		$this->ban_repository = $ban_repository;
		$this->reason_repository = $reason_repository;
		$this->util = $util;
	}

	public function render() {
		$attrs = [];
		$attrs['ban_post_ids'] = $this->ban_repository->get_all_post_ids();
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Ban_Post_List.php' );
		return ob_get_clean();
	}
}
