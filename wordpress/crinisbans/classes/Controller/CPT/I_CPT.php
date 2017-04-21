<?php
namespace crinis\cb\Controller\CPT;

interface I_CPT {
	public function get_config();
	public function meta_box( $post_id);
	public function save_post( $post_id, $post);
	public function manage_posts_columns( $columns);
	public function manage_posts_custom_column( $column, $post_id);
}
