<?php
namespace crinis\cb\Model\Repository;

use \crinis\cb\Helper\Util;
use \crinis\cb\Model\DB\DB;
use \crinis\cb\Model\Factory\I_Factory;

abstract class Repository {

	protected static $shortcode;
	protected $util;
	protected $db;
	protected $factory;

	public function __construct( Util $util, DB $db, I_Factory $factory ) {
		$this->util = $util;
		$this->db = $db;
		$this->factory = $factory;
	}

	public function get( $post_id, $publish = true ) {
		$post = get_post( $post_id );

		if ( ! $post || ($publish && 'publish' !== $post->post_status) ) {
			return null;
		}
		$row = $this->db->get_row( 'post_id',$post_id );
		return $this->factory->create( $this->create_init_data( $post,$row ) );
	}

	public function update( $new ) {
		$old = $this->get( $new->get_post_id(), false );

		do_action( 'cb_post_before_update', $new, $old );

		$post = array(
		  'ID'      => $old->get_post_id(),
		  'post_content' => sanitize_text_field( '[' . static::$shortcode . ']' ),
		);
		if ( $old->get_name() !== $new->get_name() ) {
			$post['post_name'] = sanitize_text_field( $new->get_name() );
		}
		if ( $old->get_title() !== $new->get_title() ) {
			$post['post_title'] = sanitize_text_field( $new->get_title() );
		}

		if ( ! wp_update_post( $post ) ) {
			return false;
		}

		$table_update_data = $this->get_table_update_data( $old, $new );

		if ( $this->db->exists( 'post_id',$new->get_post_id() ) ) {
			if ( ! $this->db->update_by_post_id( $new->get_post_id(),$table_update_data ) ) {
				return false;
			}
		} else {
			$table_update_data['post_id'] = $new->get_post_id();
			if ( ! $this->db->insert( $table_update_data ) ) {
				return false;
			}
		}
		do_action( 'cb_post_updated', $new, $old );

		return $new;
	}

	public function get_all( $publish = true ) {
		$rows = $this->db->get_all_rows();
		$post_ids = [];
		foreach ( $rows as $row ) {
			$post_ids[] = $row['post_id'];
		}
		if ( empty( $post_ids ) ) {
			return [];
		}
		$posts = $this->util->get_posts_by_post_ids( $post_ids );
		$objs = [];
		foreach ( $posts as $index => $post ) {
			if ( $publish && 'publish' !== $post->post_status ) {
				continue;
			}
			$objs[] = $this->factory->create( $this->create_init_data( $post,$rows[ $index ] ) );
		}
		return $objs;
	}

	public function get_all_post_ids( $publish = true ) {
		$rows = $this->db->get_all_rows();
		$post_ids = [];
		foreach ( $rows as $row ) {
			$post_ids[] = $row['post_id'];
		}
		$posts = $this->util->get_posts_by_post_ids( $post_ids );

		$filtered_post_ids = [];
		foreach ( $posts as $post ) {
			if ( $publish && 'publish' !== $post->post_status ) {
				continue;
			}
			$filtered_post_ids[] = $post->ID;
		}
		return $filtered_post_ids;
	}

	public function exists( $post_id ) {
		return $this->db->post_id_exists( $post_id );
	}

	public function post_ids_exist( $post_ids ) {
		foreach ( $post_ids as $post_id ) {
			if ( ! $this->exists( $post_id ) ) {
				return false;
			}
		}
		return true;
	}

	public function get_by_post_ids( $post_ids, $publish = true ) {
		if ( empty( $post_ids ) ) {
			return [];
		}
		$rows = $this->db->get_rows_by_post_ids( implode( ',',$post_ids ) );
		$post_ids = [];
		foreach ( $rows as $row ) {
			$post_ids[] = $row['post_id'];
		}
		$objs = [];
		$posts = $this->util->get_posts_by_post_ids( $post_ids, $publish );
		foreach ( $posts as $index => $post ) {
			if ( $publish && 'publish' !== $post->post_status ) {
				continue;
			}
			$objs[] = $this->factory->create( $this->create_init_data( $post,$rows[ $index ] ) );
		}
		return $objs;
	}

	protected function create_init_data( $post, $row ) {
		$init_data  = [];
		$init_data['post'] = $post;
		$init_data['row'] = $row;
		return $init_data;
	}

	public function update_intermediate( $db, $foreign, $obj, $old_post_ids, $new_post_ids ) {
		$removed_foreign_k = array_diff( $new_post_ids,$old_post_ids );
		$added_foreign_k = array_diff( $old_post_ids,$new_post_ids );
		foreach ( $removed_foreign_k as $foreign_k ) {
			$db->delete( $obj->get_post_id(),$foreign_k );
		}
		foreach ( $added_foreign_k as $foreign_k ) {
			$data = [];
			$data['foreign_k'] = $foreign_k;
			$data['local_k'] = $obj->get_post_id();

			$db->insert( $data );
		}
	}

	protected abstract function get_table_update_data( $current, $updated);

}
