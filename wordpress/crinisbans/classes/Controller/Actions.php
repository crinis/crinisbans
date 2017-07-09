<?php

namespace crinis\cb\Controller;
use \crinis\cb\Model\Ban;
use \crinis\cb\Model\Admin;
use \crinis\cb\Model\Group;
use \crinis\cb\Helper\Util;
use \crinis\cb\Model\Repository\Repository;
use \crinis\cb\Service\RCON_Service;
use \crinis\cb\Service\Role_Service;
use \crinis\cb\Service\Capability_Service;

class Actions {

	private $util;
	private $admin_repository;
	private $server_repository;
	private $reason_repository;
	private $rcon_service;
	private $role_service;
	private $capability_service;

	public function __construct(
		Util $util,
		Repository $admin_repository,
		Repository $server_repository,
		Repository $reason_repository,
		RCON_Service $rcon_service,
		Role_Service $role_service,
		Capability_Service $capability_service
	) {
		$this->util = $util;
		$this->admin_repository = $admin_repository;
		$this->server_repository = $server_repository;
		$this->reason_repository = $reason_repository;
		$this->rcon_service = $rcon_service;
		$this->role_service = $role_service;
		$this->capability_service = $capability_service;
	}

	public function profile_update( $user_id, $old_user_data ) {
		$admins = $this->admin_repository->get_by_user_id( $user_id );
		if ( empty( $admins ) ) {
			return;
		}
		foreach ( $admins as $admin ) {
			$admin->set_title( get_userdata( $admin->get_user_id() )->display_name );
			$this->admin_repository->update( $admin );
		}
	}

	public function before_post_update( $object ) {
		if ( $object instanceof Admin ) {
			$object->set_name( $object->get_steam_id_64() );
			$user = get_userdata( $object->get_user_id() );
			$object->set_title( $user->display_name );
		}
	}

	public function post_updated( $new, $old ) {
		if ( $new instanceof Admin || $new instanceof Group ) {
			if ( $new instanceof Admin ) {
				$this->admin_post_updated( $new, $old );
			} else {
				$this->group_post_updated( $new, $old );
			}
			$this->reload_admins();
		} elseif ( $new instanceof Ban ) {
			$this->ban_post_updated( $new, $old );
		}
	}

	private function reload_admins() {
		$servers = $this->server_repository->get_all();
		foreach ( $servers as $server ) {
			if ( $this->rcon_service->connect( $server ) ) {
				$this->rcon_service->reload_admins();
			}
		}
	}

	public function admin_post_updated( $new, $old ) {
		if ( 'publish' === $new->get_status() ) {
			$new_group_post_ids = $new->get_group_post_ids();
			$old_group_post_ids = $old->get_group_post_ids();
			$removed_group_post_ids = array_diff( $new_group_post_ids,$old_group_post_ids );
			$added_group_post_ids = array_diff( $old_group_post_ids,$new_group_post_ids );
			$added_groups = $this->group_repository->get_by_post_ids($added_group_post_ids);
			$removed_groups = $this->group_repository->get_by_post_ids($removed_group_post_ids);

			$this->role_service->add_roles_to_admin($admin, $added_groups);
			$this->role_service->remove_roles_from_admin($admin, $removed_groups);
		} else {
			$groups = $this->group_repository->get_all();
			$this->role_service->remove_roles_from_admin($admin, $groups);
		}
	}

	public function group_post_updated( $new, $old ) {
		$role = $this->role_service->create_role($new);
		$this->capability_service->set_capabilities($role,$_POST['cb-capabilities']);
		$admins = $this->admin_repository->get_by_group($new);
		if ( 'publish' === $new->get_status() ) {
			foreach ( $admins as $admin ) {
				$this->role_service->add_role_to_admin ( $new, $admin );
			}
		} else {
			foreach ( $admins as $admin ) {
				$this->role_service->remove_role_from_admin ( $new, $admin );
			}
		}
	}

	private function ban_post_updated( $object ) {
		$reason = $this->reason_repository->get( $object->get_reason_post_id() );
		if ( ! $reason ) {
			return;
		}
		$servers = $this->server_repository->get_all();
		foreach ( $servers as $server ) {
			if ( $this->rcon_service->connect( $server ) ) {
				$this->rcon_service->kick_player( $object->get_steam_id_64(),sprintf( 'You are banned from this server. Reason: %s',$reason->get_title() ) );
			}
		}
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'cb-backend', CB_URL . '/dist/backend.bundle.js',[ 'jquery-ui-widget' ] );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'cb-vue', CB_URL . '/dist/styles.css' );
	}

	public function enqueue_frontend_scripts( $cb_env ) {
		wp_enqueue_script( 'cb-vue', CB_URL . '/dist/app.bundle.js', array() );
		wp_localize_script( 'cb-vue', 'cbEnv', $cb_env );
	}
	/*
	Main Javascript is included inside of specific controllers
	*/

}

