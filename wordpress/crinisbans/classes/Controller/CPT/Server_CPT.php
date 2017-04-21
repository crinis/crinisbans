<?php
namespace crinis\cb\Controller\CPT;

use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\View\Viewhelper\I_Viewhelper;

class Server_CPT implements I_CPT {

	const POST_TYPE_NAME = 'cb-servers';
	const CAPABILITY = 'cb_server';
	const SLUG = 'servers';

	private $server_repository;
	private $viewhelper;

	public function __construct(
		Repository $server_repository,
		I_Viewhelper $viewhelper,
		Repository $server_group_repository
	) {
		$this->server_repository = $server_repository;
		$this->viewhelper = $viewhelper;
		$this->server_group_repository = $server_group_repository;
	}

	public function get_config() {
		return [
			'name' => self::POST_TYPE_NAME,
			'capability' => self::CAPABILITY,
			'slug' => self::SLUG,
			'meta_box_title' => 'Crinisbans Server',
			'supports' => [ 'title' ],
			'labels' => [
				'name'                  => _x( 'Servers', 'Post type general name', 'crinisbans' ),
				'singular_name'         => _x( 'Server', 'Post type singular name', 'crinisbans' ),
				'menu_name'             => _x( 'Servers', 'Server Menu text', 'crinisbans' ),
				'name_Server_bar'        => _x( 'Server', 'Add New on Toolbar', 'crinisbans' ),
				'add_new'               => __( 'Add new', 'crinisbans' ),
				'add_new_item'          => __( 'Add new Server', 'crinisbans' ),
				'new_item'              => __( 'New Server', 'crinisbans' ),
				'edit_item'             => __( 'Edit Server', 'crinisbans' ),
				'view_item'             => __( 'View Server', 'crinisbans' ),
				'all_items'             => __( 'All Servers', 'crinisbans' ),
				'search_items'       => __( 'Search Servers', 'crinisbans' ),
				'parent_item_colon'  => __( 'Parent Servers:', 'crinisbans' ),
				'not_found'          => __( 'No Servers found.', 'crinisbans' ),
				'not_found_in_trash' => __( 'No Servers found in trash.', 'crinisbans' ),
			],
		];
	}

	public function meta_box( $post ) {
		$attrs = [];
		$attrs['server'] = $this->server_repository->get( $post->ID, false );
		$attrs['all_server_groups'] = $this->server_group_repository->get_all( false );
		wp_nonce_field( self::POST_TYPE_NAME . '_action', self::POST_TYPE_NAME . '_nonce' );
		require( CB_PATH . 'classes/View/Templates/Server_CPT_Meta_Box.php' );
	}

	public function save_post( $post_id, $post ) {
		$server = $this->server_repository->get( $post_id, false );
		$server->set_address( $_POST['cb-server-address'] );
		$server->set_port( $_POST['cb-server-port'] );
		$server->set_gotv_address( $_POST['cb-gotv-address'] );
		$server->set_gotv_port( $_POST['cb-gotv-port'] );
		$server->set_rcon_password( $_POST['cb-rcon-password'] );
		$server->set_server_group_post_ids( $_POST['cb-server-groups'] );
		$this->server_repository->update( $server );
	}

	public function manage_posts_columns( $columns ) {
		return $columns;
	}

	public function manage_posts_custom_column( $column, $post_id ) {

	}
}
