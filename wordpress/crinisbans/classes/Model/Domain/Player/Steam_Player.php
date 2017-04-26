<?php
namespace crinis\cb\Model\Domain\Player;

class Steam_Player extends Player {

	protected $steam_profile;

	public function set_init_data( $data ) {
		parent::set_init_data( $data );
		$player_id = trim( $data['player_id'] );
		if ( $this->validator->is_steam_profile_url( $player_id ) ) {
			$player_id = $this->util->get_steam_profile_id_from_url( $player_id );
		} elseif ( $this->validator->is_steam_id( $player_id ) ) {
			$this->steam_id = $player_id;
			$player_id = \SteamId::convertSteamIdToCommunityId( $player_id );
			$this->steam_id_64 = $player_id;
		} elseif ( $this->validator->is_steam_id_64( $player_id ) ) {
			$this->steam_id_64 = $player_id;
			$this->steam_id = \SteamId::convertCommunityIdToSteamId( $player_id );
		} else {
			return false;
		}

		try {
			$this->steam_profile = \SteamId::create( sanitize_text_field( $player_id ) );
		} catch ( \Exception $e ) {
			if ( isset( $this->steam_id ) && isset( $this->steam_id_64 ) ) {
				$this->nickname = $this->steam_id_64;
				return true;
			}
			return false;
		}
		try {
			$this->steam_id_64 = $this->steam_profile->getSteamId64();
			$this->steam_id = \SteamId::convertCommunityIdToSteamId( $this->steam_id_64 );
			$this->nickname = $this->steam_profile->getNickname();
		} catch ( \SteamCondenserException $e ) {
			return false;
		}
		return true;
	}

	public function get_steam_profile_url() {
		return $this->steam_profile ? $this->steam_profile->getBaseUrl():null;
	}

	public function get_recent_playtime() {
		return $this->steam_profile ? $this->steam_profile->getRecentPlaytime():null;
	}

	public function get_total_playtime() {
		return $this->steam_profile ? $this->steam_profile->getTotalPlaytime():null;
	}

	public function is_vac_banned() {
		return $this->steam_profile ? $this->steam_profile->isBanned():null;
	}

	public function is_profile_public() {
		return $this->steam_profile ? $this->steam_profile->isPublic():null;
	}

	public function has_steam_profile() {
		return $this->steam_profile ? true:false;
	}
}
