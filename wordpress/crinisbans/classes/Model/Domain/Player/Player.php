<?php
namespace crinis\cb\Model\Domain\Player;
use \crinis\cb\Helper\Util;
use \crinis\cb\Model\Validator;

abstract class Player {
	protected $steam_id_64;
	protected $steam_id;
	protected $nickname;
	protected $util;
	protected $validator;

	public function __construct( Util $util, Validator $validator ) {
		$this->util = $util;
		$this->validator = $validator;
	}

	public function set_init_data( $data ) {

	}

	public function get_nickname() {
		return $this->nickname;
	}

	public function get_steam_id_64() {
		return $this->steam_id_64;
	}

	public function get_steam_id() {
		return $this->steam_id;
	}

}
