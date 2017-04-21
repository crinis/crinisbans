<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Server_Post_Shortcode extends Post_Shortcode {
	protected static $name = 'cb_server_show';

	private $server_repository;
	private $server_group_repository;
	private $util;

	public function __construct( Repository $server_repository, Repository $server_group_repository, Util $util ) {
		$this->server_repository = $server_repository;
		$this->server_group_repository = $server_group_repository;
		$this->util = $util;
	}

	public function render() {
		global $post;
		$attrs = [];
		$attrs['server'] = $this->server_repository->get( $post->ID );
		$attrs['server_groups'] = $this->server_group_repository->get_by_post_ids( $attrs['server']->get_server_group_post_ids() );
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Server_Post.php' );
		return ob_get_clean();
	}
}
