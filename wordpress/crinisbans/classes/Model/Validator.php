<?php
namespace crinis\cb\Model;

class Validator {

	public function is_server_address( $address ) {
		return is_string( $address );
	}

	public function is_integer( $int ) {
		return is_numeric( $int ) ? true : false;
	}

	public function is_steam_id_64( $steam_id_64 ) {
		return $this->is_integer( $steam_id_64 ) ? true : false;
	}

	public function is_steam_id( $steam_id ) {
			return preg_match( '/^STEAM_[01]:[01]:\d+$/', $steam_id ) || preg_match( '/^\[U:[0-1]:[0-9]+\]$/', $steam_id );
	}

	public function is_steam_profile_url( $url ) {
		return preg_match( '/^(https?)?:\/\/(www)?\.?steamcommunity.com\/(id|profiles)\/(.*)$/',$url );
	}

	public function is_user_id( $user_id ) {
		return get_userdata( $user_id ) !== false ? true : false;
	}

	public function is_immunity( $immunity ) {
		return (($this->is_integer( $immunity ) && $immunity >= 0 && $immunity <= 100) || (empty( $immunity ))) ? true : false;
	}

	public function is_date( $date ) {
		$format = 'Y-m-d H:i:s';
	    $d = \DateTime::createFromFormat( $format, $date );
	    return $d && $d->format( $format ) === $date;
	}

}
