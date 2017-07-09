<?php
namespace crinis\cb\Controller\CPT;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Model\Factory\I_Factory;
use \crinis\cb\Helper\Util;
use \crinis\cb\View\Viewhelper\I_Viewhelper;
use \crinis\cb\Model\Options;

class Admin_CPT implements I_CPT {

	const POST_TYPE_NAME = 'cb-admins';
	const CAPABILITY = 'cb_admin';
	const SLUG = 'admins';

	private $admin_repository;
	private $group_repository;
	private $player_factory;
	private $viewhelper;
	private $options;
	private $util;

	public function __construct(
		Repository $admin_repository,
		Repository $group_repository,
		I_Viewhelper $viewhelper,
		options $options,
		Util $util,
		I_Factory $player_factory
	) {
		$this->admin_repository = $admin_repository;
		$this->group_repository = $group_repository;
		$this->viewhelper = $viewhelper;
		$this->util = $util;
		$this->options = $options;
		$this->player_factory = $player_factory;
	}

	public function get_config() {
		return [
			'name' => self::POST_TYPE_NAME,
			'capability' => self::CAPABILITY,
			'slug' => self::SLUG,
			'meta_box_title' => 'Crinisbans Admin',
			'supports' => [],
			'labels' => [
				'name'                  => _x( 'Admins', 'Post type general name', 'crinisbans' ),
				'singular_name'         => _x( 'Admin', 'Post type singular name', 'crinisbans' ),
				'menu_name'             => _x( 'Admins', 'Admin Menu text', 'crinisbans' ),
				'name_admin_bar'        => _x( 'Admin', 'Add New on Toolbar', 'crinisbans' ),
				'add_new'               => __( 'Add new', 'crinisbans' ),
				'add_new_item'          => __( 'Add new admin', 'crinisbans' ),
				'new_item'              => __( 'New admin', 'crinisbans' ),
				'edit_item'             => __( 'Edit admin', 'crinisbans' ),
				'view_item'             => __( 'View admin', 'crinisbans' ),
				'all_items'             => __( 'All admins', 'crinisbans' ),
				'search_items'       => __( 'Search admins', 'crinisbans' ),
				'parent_item_colon'  => __( 'Parent admins:', 'crinisbans' ),
				'not_found'          => __( 'No admins found.', 'crinisbans' ),
				'not_found_in_trash' => __( 'No admins found in trash.', 'crinisbans' ),
			],
		];
	}

	public function meta_box( $post ) {
		$attrs = [];
		$attrs['admin'] = $this->admin_repository->get( $post->ID, false );
		$attrs['all_groups'] = $this->group_repository->get_all( false );
		wp_nonce_field( self::POST_TYPE_NAME . '_action', self::POST_TYPE_NAME . '_nonce' );
		require( CB_PATH . 'classes/View/Templates/Admin_CPT_Meta_Box.php' );
	}

	public function save_post( $post_id, $post ) {
		$admin = $this->admin_repository->get( $post_id, false );
		$steam_ids_64 = [];
		foreach ( explode( ',',$_POST['cb-player-ids'] ) as $player_id ) {
			$player = $this->player_factory->create( [
				'player_id' => $player_id,
			] );
			if ( ! $player ) {
				continue;
			}
			$steam_ids_64[] = $player->get_steam_id_64();
		}

		$admin->set_steam_ids_64( $steam_ids_64 );

		$admin->set_user_id( $_POST['cb-user-id'] );
		$admin->set_group_post_ids( $_POST['cb-groups'] );

		$this->admin_repository->update( $admin );
	}

	public function manage_posts_columns( $columns ) {
		$columns['author'] = esc_html( __( 'Author' ) );
		return $columns;
	}

	public function manage_posts_custom_column( $column, $post_id ) {

	}
}
