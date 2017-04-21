<?php
namespace crinis\cb\Controller;
use \crinis\cb\Controller\CPT\I_CPT;

class Register_CPT implements I_Register_CPT {

	private $loader;
	private $cpts;

	public function __construct( Loader $loader ) {
		$this->loader = $loader;
		$this->cpts = [];
		$this->loader->add_action( 'init', $this, 'register', 10 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'add_meta_boxes', 10, 2 );
		$this->loader->add_action( 'save_post', $this, 'save_post', 10, 2 );
	}

	public function add( I_CPT $cpt ) {
		$this->cpts[] = $cpt;
		$this->loader->add_action( 'manage_' . $cpt::POST_TYPE_NAME . '_posts_custom_column', $cpt, 'manage_posts_custom_column', 10, 2 );
		$this->loader->add_filter( 'manage_' . $cpt::POST_TYPE_NAME . '_posts_columns', $cpt, 'manage_posts_columns' );
		$this->loader->add_action( 'init', $this, 'register' );
	}

	public function add_meta_boxes( $post_id ) {
		foreach ( $this->cpts as $cpt ) {
			$config = $cpt->get_config();
			$screens = array( $config['name'] );
			foreach ( $screens as $screen ) {
				add_meta_box(
					$config['name'] . '_metabox',
					$config['meta_box_title'],
					[ $cpt, 'meta_box' ],
					$screen,'normal','high'
				);
			}
		}
	}

	public function register() {
		foreach ( $this->cpts as $cpt ) {
			$config = $cpt->get_config();
			$cpt_args = array(
				'labels' => $config['labels'],
				'has_archive' => false,
				'public' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_in_admin_bar' => true,
				'supports' => array_merge( [ 'thumbnail', 'page-attributes', 'comments', 'excerpt' ], $config['supports'] ),
				'capabilities' => array(
					'read' => $config['capability'],
			        'edit_post' => $config['capability'],
			        'edit_posts' => $config['capability'],
			        'edit_others_posts' => $config['capability'],
			        'edit_private_posts' => $config['capability'],
			        'edit_published_posts' => $config['capability'],
			        'create_posts' => $config['capability'],
			        'publish_posts' => $config['capability'],
			        'read_private_posts' => $config['capability'],
			        'delete_post' => 'cb_delete',
			        'delete_posts' => 'cb_delete',
			        'delete_private_posts' => 'cb_delete',
			        'delete_published_posts' => 'cb_delete',
			        'delete_others_posts' => 'cb_delete',
	    		),
				'rewrite' => [
					'slug' => $config['slug'],
				],
			);
			register_post_type( $config['name'], $cpt_args );
		}
	}

	public function save_post( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $post_id;
		}
		if ( false !== wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}
		$post_type = get_post_type_object( $post->post_type );
		foreach ( $this->cpts as $cpt ) {
			if ( $post_type->name === $cpt::POST_TYPE_NAME
				&& isset( $_POST[ $cpt::POST_TYPE_NAME . '_nonce' ] )
				&& wp_verify_nonce( $_POST[ $cpt::POST_TYPE_NAME . '_nonce' ], $cpt::POST_TYPE_NAME . '_action' )
				&& current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				remove_action( 'save_post', array( $this, 'save_post' ) );
				$cpt->save_post( $post_id, $post );
			}
		}

	}

}
