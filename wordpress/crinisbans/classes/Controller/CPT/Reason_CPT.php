<?php
namespace crinis\cb\Controller\CPT;
use \crinis\cb\Model\Repository\Repository;

class Reason_CPT implements I_CPT {

	const POST_TYPE_NAME = 'cb-reasons';
	const CAPABILITY = 'cb_reason';
	const SLUG = 'ban-reasons';

	private $reason_repository;

	public function __construct( Repository $reason_repository ) {
		$this->reason_repository = $reason_repository;
	}

	public function get_config() {
		return [
			'name' => self::POST_TYPE_NAME,
			'capability' => self::CAPABILITY,
			'slug' => self::SLUG,
			'meta_box_title' => 'Crinisbans Ban Reason',
			'supports' => [ 'title' ],
			'labels' => [
				'name'                  => _x( 'Reasons', 'Post type general name', 'crinisbans' ),
				'singular_name'         => _x( 'Reason', 'Post type singular name', 'crinisbans' ),
				'menu_name'             => _x( 'Reasons', 'Reason Menu text', 'crinisbans' ),
				'name_Reason_bar'        => _x( 'Reason', 'Add New on Toolbar', 'crinisbans' ),
				'add_new'               => __( 'Add new', 'crinisbans' ),
				'add_new_item'          => __( 'Add new Reason', 'crinisbans' ),
				'new_item'              => __( 'New Reason', 'crinisbans' ),
				'edit_item'             => __( 'Edit Reason', 'crinisbans' ),
				'view_item'             => __( 'View Reason', 'crinisbans' ),
				'all_items'             => __( 'All Reasons', 'crinisbans' ),
				'search_items'       => __( 'Search Reasons', 'crinisbans' ),
				'parent_item_colon'  => __( 'Parent Reasons:', 'crinisbans' ),
				'not_found'          => __( 'No Reasons found.', 'crinisbans' ),
				'not_found_in_trash' => __( 'No Reasons found in trash.', 'crinisbans' ),
			],
		];
	}

	public function meta_box( $post ) {
		$attrs = [];
		$attrs['reason'] = $this->reason_repository->get( $post->ID, false );
		wp_nonce_field( self::POST_TYPE_NAME . '_action', self::POST_TYPE_NAME . '_nonce' );
		require( CB_PATH . 'classes/View/Templates/Reason_CPT_Meta_Box.php' );
	}

	public function save_post( $post_id, $post ) {
		$reason = $this->reason_repository->get( $post_id, false );
		$reason->set_duration( $_POST['cb-duration'] );
		$this->reason_repository->update( $reason );
	}

	public function manage_posts_columns( $columns ) {
		return $columns;
	}

	public function manage_posts_custom_column( $column, $post_id ) {

	}
}
