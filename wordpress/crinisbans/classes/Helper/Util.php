<?php
namespace crinis\cb\Helper;

class Util {

	public function get_loop( $post_ids, $is_paged = false, $order = 'DESC', $orderby = 'date' ) {
		global $post;
		$query_array = [
			'post_type' => 'any',
			'post__in' => ! empty( $post_ids ) ? $post_ids : [ '0' ],
			'orderby' => $orderby,
			'order' => $order,
			'ignore_sticky_posts' => 1,
			'posts_per_page' => -1,
		];
		if ( $is_paged ) {
			$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
			$query_array['paged'] = $paged;
			$query_array['posts_per_page'] = 20;
		}
		return new \WP_Query( $query_array );
	}

	public function get_posts_by_post_ids( $post_ids, $order = 'desc', $orderby = 'post__in' ) {
		$wp_query = $this->get_loop( $post_ids, false, $order, $orderby );
		return $wp_query->posts;
	}

	public function encrypt( $string ) {
		return openssl_encrypt($string,
			'AES-128-ECB',
		SECURE_AUTH_KEY);
	}

	public function decrypt( $string ) {
		return openssl_decrypt($string,
			'AES-128-ECB',
		SECURE_AUTH_KEY);
	}

	public function get_steam_profile_id_from_url( $url ) {
		$url = rtrim( $url, '/' );
		$path = wp_parse_url( $url,PHP_URL_PATH );
		$paths = explode( '/',$path );
		return end( $paths );
	}

	public function get_url_from_steam_id_64( $steam_id_64 ) {
		return sprintf( 'https://steamcommunity.com/profiles/%s',$steam_id_64 );
	}

	public function get_url_from_steam_id( $steam_id ) {
		return $this->get_url_from_steam_id_64( $this->get_steam_id_64_from_steam_id( $steam_id ) );
	}

	public function get_formatted_datetime( $date ) {
		$datetime = new \DateTime( $date );
		return $datetime->format( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
	}

	public function get_steam_id_from_steam_id_64( $steam_id_64 ) {
		try {
			return \SteamId::convertCommunityIdToSteamId( $steam_id_64 );
		} catch ( \SteamCondenserException $e ) {
			return null;
		}
	}

	public function get_steam_id_64_from_steam_id( $steam_id ) {
		try {
			return \SteamId::convertSteamIdToCommunityId( $steam_id );
		} catch ( \SteamCondenserException $e ) {
			return null;
		}
	}

	public function explain_flag( $flag_name ) {
		switch ( $flag_name ) {
		    case 'flag_z':
		        return __( 'Magically enables all flags and ignores immunity values.' );
		    case 'flag_a':
		        return __( 'Reserved slot access.' );
		    case 'flag_b':
		        return __( 'Generic admin; required for admins.' );
		    case 'flag_c':
		        return __( 'Kick other players.' );
		    case 'flag_d':
		        return __( 'Ban other players.' );
		    case 'flag_e':
		        return __( 'Remove bans.' );
		    case 'flag_f':
		        return __( 'Slay/harm other players.' );
		    case 'flag_g':
		        return __( 'Change the map or major gameplay features.' );
		    case 'flag_h':
		        return __( 'Change most cvars.' );
		    case 'flag_i':
		        return __( 'Execute config files.' );
		    case 'flag_j':
		        return __( 'Special chat privileges.' );
		    case 'flag_k':
		        return __( 'Start or create votes.' );
		    case 'flag_l':
		        return __( 'Set a password on the server.' );
		    case 'flag_m':
		        return __( 'Use RCON commands.' );
		    case 'flag_n':
		        return __( 'Change sv_cheats or use cheating commands.' );
		    case 'flag_o':
		        return __( 'Custom Group 1.' );
		    case 'flag_p':
		        return __( 'Custom Group 2.' );
		    case 'flag_q':
		        return __( 'Custom Group 3.' );
		    case 'flag_r':
		        return __( 'Custom Group 4.' );
		    case 'flag_s':
		        return __( 'Custom Group 5.' );
		    case 'flag_t':
		        return __( 'Custom Group 6.' );
		    default:
		    	return '';
		}// End switch().
	}

	public function convert_seconds_to_duration( $seconds ) {
		$dt1 = new \DateTime( '@0' );
		$dt2 = new \DateTime( "@$seconds" );
			$diff = $dt2->diff( $dt1 );
			$duration_parts = array(
				__( 'years','crinisbans' ) => $diff->y,
				__( 'months','crinisbans' ) => $diff->m,
				__( 'days','crinisbans' ) => $diff->d,
				__( 'hours','crinisbans' ) => $diff->h,
				__( 'minutes','crinisbans' ) => $diff->i,
				__( 'seconds','crinisbans' ) => $diff->s,
			);
			$duration_str = '';

		foreach ( $duration_parts as $duration_type => $duration_value ) {
			if ( $duration_value < 1 ) {
				continue;
			}
			$duration_str .= $duration_value . ' ' . $duration_type . ', ';
		}

			return rtrim( $duration_str,', ' );
	}

	public function get_array_by_matching_keys( $array, $regex ) {
		$result = [];
		foreach ( $array as $key => $value ) {
			if ( preg_match( $regex, $key ) ) {
				$result[ $key ] = $array[ $key ];
			}
		}
		return $result;
	}

}
