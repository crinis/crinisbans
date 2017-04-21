<?php
namespace crinis\cb\Controller\Shortcodes;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Helper\Util;

class Ban_Post_Shortcode extends Post_Shortcode {
	protected static $name = 'cb_ban_show';

	private $ban_repository;
	private $reason_repository;
	private $util;

	public function __construct( Repository $ban_repository, Repository $reason_repository, Util $util ) {
		$this->ban_repository = $ban_repository;
		$this->reason_repository = $reason_repository;
		$this->util = $util;
	}

	public function render() {
		global $post;
		$attrs = [];
		$attrs['ban'] = $this->ban_repository->get( $post->ID );
		$attrs['reason'] = $this->reason_repository->get( $attrs['ban']->get_reason_post_id() );
		$date = new \DateTime( $attrs['ban']->get_date_gmt() );
		$date->add( new \DateInterval( 'PT' . $attrs['reason']->get_duration() . 'S' ) );
		$attrs['ban_end'] = $this->util->get_formatted_datetime( $date->format( 'Y-m-d H:i:s' ) );
		ob_start();
		require( CB_PATH . 'classes/View/Templates/Ban_Post.php' );
		return ob_get_clean();
	}
}
