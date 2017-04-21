<?php
namespace crinis\cb\Controller\CPT;

use \crinis\cb\Model\Repository\Repository;

class Server_Group_CPT implements I_CPT {

	const POST_TYPE_NAME = 'cb-server-groups';
	const CAPABILITY = 'cb_server_group';
	const SLUG = 'server-groups';

	private $server_group_repository;

	public function __construct( Repository $server_group_repository ) {
		$this->server_group_repository = $server_group_repository;
	}

	public function get_config() {
		return [
			'name' => self::POST_TYPE_NAME,
			'capability' => self::CAPABILITY,
			'slug' => self::SLUG,
			'meta_box_title' => 'Crinisbans Server Group',
			'supports' => [ 'title' ],
			'labels' => [
				'name'                  => _x( 'Server Groups', 'Post type general name', 'crinisbans' ),
				'singular_name'         => _x( 'Server Group', 'Post type singular name', 'crinisbans' ),
				'menu_name'             => _x( 'Server Groups', 'Server Group Menu text', 'crinisbans' ),
				'name_Server_Group_bar'        => _x( 'Server Group', 'Add New on Toolbar', 'crinisbans' ),
				'add_new'               => __( 'Add new', 'crinisbans' ),
				'add_new_item'          => __( 'Add new Server Group', 'crinisbans' ),
				'new_item'              => __( 'New Server Group', 'crinisbans' ),
				'edit_item'             => __( 'Edit Server Group', 'crinisbans' ),
				'view_item'             => __( 'View Server Group', 'crinisbans' ),
				'all_items'             => __( 'All Server Groups', 'crinisbans' ),
				'search_items'       => __( 'Search Server Groups', 'crinisbans' ),
				'parent_item_colon'  => __( 'Parent Server Groups:', 'crinisbans' ),
				'not_found'          => __( 'No Server Groups found.', 'crinisbans' ),
				'not_found_in_trash' => __( 'No Server Groups found in trash.', 'crinisbans' ),
			],
		];
	}

	public function meta_box( $post ) {
		$attrs = [];
		$attrs['server_group'] = $this->server_group_repository->get( $post->ID, false );
		wp_nonce_field( self::POST_TYPE_NAME . '_action', self::POST_TYPE_NAME . '_nonce' );
	}

	public function save_post( $post_id, $post ) {
		$server_group = $this->server_group_repository->get( $post_id, false );
		$this->server_group_repository->update( $server_group );
	}

	public function manage_posts_columns( $columns ) {
		return $columns;
	}

	public function manage_posts_custom_column( $column, $post_id ) {

	}
}
