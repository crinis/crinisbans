<?php
namespace crinis\cb\Controller\Shortcodes;

abstract class Shortcode implements I_Shortcode {

	protected static $name = '';
	protected $repository;

	public abstract function render_base();

	public static function get_name() {
		return static::$name;
	}
}
