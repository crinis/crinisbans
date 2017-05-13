<?php
namespace crinis\cb\Model;

use \crinis\cb\Helper\Util;
use \crinis\cb\Model\Validator;

abstract class Model implements \JsonSerializable {

	protected $validator;
	protected $util;
	protected $post_id;
	protected $title;
	protected $name;
	protected $content;
	protected $date_gmt;
	protected $status;
	protected $excerpt;
	protected $author;

	public function __construct( Util $util, Validator $validator ) {
		$this->util = $util;
		$this->validator = $validator;
	}

	public function set_init_data( $data ) {
		$this->post_id = $data['post']->ID;
		$this->title = $data['post']->post_title;
		$this->name = $data['post']->post_name;
		$this->content = $data['post']->post_content;
		$this->date_gmt = $data['post']->post_date_gmt;
		$this->excerpt = $data['post']->post_excerpt;
		$this->status = $data['post']->post_status;
		$this->author = $data['post']->post_author;
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function get_title() {
		return $this->title;
	}

	public function set_title( $title ) {
		if ( empty( $title ) ) {
			return false;
		}
		$this->title = $title;
		return true;
	}

	public function get_name() {
		return $this->name;
	}

	public function set_name( $name ) {
		if ( empty( $name ) ) {
			return false;
		}
		$this->name = $name;
		return true;
	}

	public function get_content() {
		return $this->content;
	}

	public function get_excerpt() {
		return $this->excerpt;
	}

	public function has_excerpt() {
		return $this->excerpt ? true : false;
	}

	public function get_permalink() {
		return get_permalink( $this->post_id );
	}

	public function get_date_gmt() {
		return $this->date_gmt;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_author() {
		return $this->author;
	}

	public function jsonSerialize() {
		return apply_filters(
			'cb_json_serialize',
			[
			'title' => $this->get_title(),
			'postID' => $this->get_post_id(),
			'excerpt' => $this->get_excerpt(),
			'content' => $this->get_content(),
			'permaLink' => $this->get_permalink(),
			],
			$this
		);
	}
}
