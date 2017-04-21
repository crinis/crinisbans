<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Reason_Post_Shortcode extends Post_Shortcode {

	protected static $name = 'cb_reason_show';

	private $reason_repository;
	private $util;

	public function __construct( Repository $reason_repository, Util $util ) {
		$this->reason_repository = $reason_repository;
		$this->util = $util;
	}

	public function render() {
		global $post;
		$attrs = [];
		$attrs['reason'] = $this->reason_repository->get( $post->ID );
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Reason_Post.php' );
		return ob_get_clean();
	}
}
