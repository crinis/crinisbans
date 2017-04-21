<?php
namespace crinis\cb\Model\Factory;
use \crinis\cb\Helper\Util;
use \crinis\cb\Model\Validator;

abstract class Factory implements I_Factory {

	protected $util;
	protected $validator;

	public function __construct( Util $util, Validator $validator ) {
		$this->util = $util;
		$this->validator = $validator;
	}

	public abstract function create( $data);
}
