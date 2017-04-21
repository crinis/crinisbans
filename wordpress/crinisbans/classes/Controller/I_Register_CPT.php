<?php
namespace crinis\cb\Controller;
use \crinis\cb\Controller\CPT\I_CPT;

interface I_Register_CPT {
	public function add( I_CPT $cpt);
}
