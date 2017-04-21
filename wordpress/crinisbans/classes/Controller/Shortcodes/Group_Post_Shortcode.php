<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Group_Post_Shortcode extends Post_Shortcode {
	protected static $name = 'cb_group_show';

	private $admin_repository;
	private $group_repository;
	private $util;

	public function __construct( Repository $admin_repository, Repository $group_repository, Util $util ) {
		$this->admin_repository = $admin_repository;
		$this->group_repository = $group_repository;
		$this->util = $util;
	}

	public function render() {
		global $post;
		$attrs = [];
		$attrs['group'] = $this->group_repository->get( $post->ID );
		$attrs['admins'] = $this->admin_repository->get_by_group( $attrs['group'] );
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Group_Post.php' );
		return ob_get_clean();
	}
}
