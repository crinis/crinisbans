<?php
namespace crinis\cb\Model\Factory;
use \crinis\cb\Helper\Util;
use \crinis\cb\Model\Validator;

interface I_Factory {

	public function __construct( Util $util, Validator $validator);

	public function create( $data);
}
