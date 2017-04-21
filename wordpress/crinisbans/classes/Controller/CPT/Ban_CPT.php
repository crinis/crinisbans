<?php
namespace crinis\cb\Controller\CPT;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Model\Factory\I_Factory;

class Ban_CPT implements I_CPT {

	const POST_TYPE_NAME = 'cb-bans';
	const CAPABILITY = 'cb_ban';
	const SLUG = 'bans';

	private $ban_repository;
	private $reason_repository;
	private $player_factory;

	public function __construct( Repository $ban_repository, I_Factory $player_factory, Repository $reason_repository ) {
		$this->ban_repository = $ban_repository;
		$this->reason_repository = $reason_repository;
		$this->player_factory = $player_factory;
	}

	public function get_config() {
		return [
			'name' => self::POST_TYPE_NAME,
			'capability' => self::CAPABILITY,
			'slug' => self::SLUG,
			'meta_box_title' => 'Crinisbans Ban',
			'supports' => [],
			'labels' => [
				'name'                  => _x( 'Bans', 'Post type general name', 'crinisbans' ),
				'singular_name'         => _x( 'Ban', 'Post type singular name', 'crinisbans' ),
				'menu_name'             => _x( 'Bans', 'Admin Menu text', 'crinisbans' ),
				'name_admin_bar'        => _x( 'Ban', 'Add New on Toolbar', 'crinisbans' ),
				'add_new'               => __( 'Add new', 'crinisbans' ),
				'add_new_item'          => __( 'Add new Ban', 'crinisbans' ),
				'new_item'              => __( 'New Ban', 'crinisbans' ),
				'edit_item'             => __( 'Edit Ban', 'crinisbans' ),
				'view_item'             => __( 'View Ban', 'crinisbans' ),
				'all_items'             => __( 'All Bans', 'crinisbans' ),
				'search_items'       => __( 'Search Bans', 'crinisbans' ),
				'parent_item_colon'  => __( 'Parent Bans:', 'crinisbans' ),
				'not_found'          => __( 'No Bans found.', 'crinisbans' ),
				'not_found_in_trash' => __( 'No Bans found in trash.', 'crinisbans' ),
			],
		];
	}

	public function meta_box( $post ) {
		$attrs = [];
		$attrs['ban'] = $this->ban_repository->get( $post->ID, false );
		$attrs['reason'] = $this->reason_repository->get( $attrs['ban']->get_reason_post_id(),false );
		$attrs['all_reasons'] = $this->reason_repository->get_all( false );
		wp_nonce_field( self::POST_TYPE_NAME . '_action', self::POST_TYPE_NAME . '_nonce' );
		require( CB_PATH . 'classes/View/Templates/Ban_CPT_Meta_Box.php' );
	}

	public function save_post( $post_id, $post ) {
		$ban = $this->ban_repository->get( $post_id, false );
		$player = $this->player_factory->create( [
			'player_id' => $_POST['cb-player-id'],
		] );
		if ( $player ) {
			$ban->set_steam_id_64( $player->get_steam_id_64() );
		}
		$ban->set_reason_post_id( $_POST['cb-reason'] );
		$this->ban_repository->update( $ban );
	}

	public function manage_posts_columns( $columns ) {
		$columns['cb_reason'] = esc_html__( 'Ban Reason','crinisbans' );
		$columns['author'] = esc_html__( 'Author' );
		return $columns;
	}

	public function manage_posts_custom_column( $column, $post_id ) {
		if ( 'cb_reason' === $column ) {
			$ban = $this->ban_repository->get( $post_id, false );
			$reason = $this->reason_repository->get( $ban->get_reason_post_id(), false );
			if ( $reason ) {
				echo esc_html( $reason->get_title(),'crinisbans' );
			}
		}
	}
}
