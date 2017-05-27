<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Server_Group_Post_Shortcode extends Post_Shortcode {
	protected static $name = 'cb_server_group_show';

	private $server_group_repository;
	private $util;

	public function __construct( Repository $server_group_repository, Util $util ) {
		$this->server_group_repository = $server_group_repository;
		$this->util = $util;
	}

	public function render() {
		global $post;
		$attrs = [];
		$attrs['server_group'] = $this->server_group_repository->get( $post->ID );
	   	$cb_env = [];
		$cb_env['ajaxUrl'] = admin_url( 'admin-ajax.php' );
		$cb_env['serverGroup'] = $attrs['server_group'];
		$cb_env['nonce'] = wp_create_nonce( 'cb_server_ajax' );
		$cb_env['text']['serverGroupLink'] = __( 'Visit detailed page of this server group','crinisbans' );
		$cb_env['text']['serverLink'] = __( 'Visit detailed page of this server','crinisbans' );
		$cb_env['text']['nickName'] = __( 'Nickname','crinisbans' );
		$cb_env['text']['steamID64'] = __( 'Steam ID 64','crinisbans' );
		$cb_env['text']['steamProfile'] = __( 'Steam Profile','crinisbans' );
		$cb_env['text']['score'] = __( 'Score','crinisbans' );
		$cb_env['text']['serverConnect'] = __( 'Connect to gameserver','crinisbans' );
		$cb_env['text']['gotvConnect'] = __( 'Connect to GOTV','crinisbans' );
		$cb_env['text']['viewPlayers'] = __( 'View current players','crinisbans' );

		do_action( 'cb_enqueue_frontend_scripts', $cb_env );

		ob_start();
		require( CB_PATH . 'classes/View/Templates/Server_Group_Post.php' );
		return ob_get_clean();
	}
}
