<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Group_Post_List_Shortcode extends Post_List_Shortcode {

	protected static $name = 'cb_group_list_sc';

	private $group_repository;
	private $admin_repository;
	private $util;

	public function __construct( Repository $group_repository, Repository $admin_repository, Util $util ) {
		$this->group_repository = $group_repository;
		$this->admin_repository = $admin_repository;
		$this->util = $util;
	}

	public function render() {
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Group_Post_List.php' );
		return ob_get_clean();
	}
}
