<?php
namespace crinis\cb\Controller\CPT;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\View\Viewhelper\I_Viewhelper;
use \crinis\cb\Helper\Util;
use \crinis\cb\Service\Role_Service;
use \crinis\cb\Service\Capability_Service;

class Group_CPT implements I_CPT {

	const POST_TYPE_NAME = 'cb-groups';
	const CAPABILITY = 'cb_group';
	const SLUG = 'admin-groups';

	private $group_repository;
	private $util;
	private $viewhelper;
	private $role_service;
	private $capability_service;

	public function __construct(
		Repository $group_repository,
		I_Viewhelper $viewhelper,
		Util $util,
		Role_Service $role_service,
		Capability_Service $capability_service
	) {
		$this->group_repository = $group_repository;
		$this->viewhelper = $viewhelper;
		$this->util = $util;
		$this->role_service = $role_service;
		$this->capability_service = $capability_service;
	}

	public function get_config() {
		return [
			'name' => self::POST_TYPE_NAME,
			'capability' => self::CAPABILITY,
			'slug' => self::SLUG,
			'meta_box_title' => 'Crinisbans Group',
			'supports' => [ 'title' ],
			'labels' => [
				'name'                  => _x( 'Groups', 'Post type general name', 'crinisbans' ),
				'singular_name'         => _x( 'Group', 'Post type singular name', 'crinisbans' ),
				'menu_name'             => _x( 'Groups', 'Group Menu text', 'crinisbans' ),
				'name_Group_bar'        => _x( 'Group', 'Add New on Toolbar', 'crinisbans' ),
				'add_new'               => __( 'Add new', 'crinisbans' ),
				'add_new_item'          => __( 'Add new Group', 'crinisbans' ),
				'new_item'              => __( 'New Group', 'crinisbans' ),
				'edit_item'             => __( 'Edit Group', 'crinisbans' ),
				'view_item'             => __( 'View Group', 'crinisbans' ),
				'all_items'             => __( 'All Groups', 'crinisbans' ),
				'search_items'       => __( 'Search Groups', 'crinisbans' ),
				'parent_item_colon'  => __( 'Parent Groups:', 'crinisbans' ),
				'not_found'          => __( 'No Groups found.', 'crinisbans' ),
				'not_found_in_trash' => __( 'No Groups found in trash.', 'crinisbans' ),
			],
		];
	}

	public function meta_box( $post ) {
		$attrs = [];
		$attrs['group'] = $this->group_repository->get( $post->ID, false );
		$attrs['all_flags'] = $this->group_repository->get_all_flags();
		$role = $this->role_service->get_role($attrs['group']);
		$attrs['capabilities'] = $this->capability_service->get_capabilities($role);

		wp_nonce_field( self::POST_TYPE_NAME . '_action', self::POST_TYPE_NAME . '_nonce' );
		require( CB_PATH . 'classes/View/Templates/Group_CPT_Meta_Box.php' );
	}

	public function save_post( $post_id, $post ) {
		$group = $this->group_repository->get( $post_id, false );
		$group->set_flags( $_POST['cb-flags'] );
		$group->set_immunity( $_POST['cb-immunity'] );
		$this->group_repository->update( $group );
	}

	public function manage_posts_columns( $columns ) {
		return $columns;
	}

	public function manage_posts_custom_column( $column, $post_id ) {

	}
}
