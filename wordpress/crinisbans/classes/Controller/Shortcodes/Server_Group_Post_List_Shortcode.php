<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;

class Server_Group_Post_List_Shortcode extends Post_List_Shortcode {

	protected static $name = 'cb_server_group_list_sc';

	private $server_group_repository;

	public function __construct( Repository $server_group_repository ) {
		$this->server_group_repository = $server_group_repository;
	}

	public function render() {
		$cb_env = [];
		$cb_env['ajaxUrl'] = admin_url( 'admin-ajax.php' );
		$cb_env['serverGroups'] = $this->server_group_repository->get_all();
		$cb_env['nonce'] = wp_create_nonce( 'cb_server_ajax' );
		$cb_env['text'] = [];
		$cb_env['text']['serverGroupLink'] = __( 'Visit detailed page of this server group','crinisbans' );
		$cb_env['text']['serverLink'] = __( 'Visit detailed page of this server','crinisbans' );
		$cb_env['text']['nickName'] = __( 'Nickname','crinisbans' );
		$cb_env['text']['steamId64'] = __( 'Steam ID 64','crinisbans' );
		$cb_env['text']['steamProfile'] = __( 'Steam Profile','crinisbans' );
		$cb_env['text']['score'] = __( 'Score','crinisbans' );
		$cb_env['text']['serverConnect'] = __( 'Connect to gameserver','crinisbans' );
		$cb_env['text']['gotvConnect'] = __( 'Connect to GOTV','crinisbans' );
		$cb_env['text']['viewPlayers'] = __( 'View current players','crinisbans' );
		wp_enqueue_script( 'cb-vue', CB_URL . '/dist/app.bundle.js', array() );
		wp_localize_script( 'cb-vue', 'cbEnv', $cb_env );
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Server_Group_Post_List.php' );
		return ob_get_clean();
	}
}
