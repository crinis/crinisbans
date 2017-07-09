<?php
namespace crinis\cb\Service;

class Role_Service {

    public function create_role($group) {
        $role_name = $this->get_role_name($group);
        $role = add_role( $role_name, $role_name );
        if ( !$role ) {
            $role = $this->get_role($group);
        }
        return $role;
    }

    public function remove_role($group) {
        $role = $this->get_role_name($group);
        return remove_role( $role );
    }

    public function add_roles_to_admin ($admin, $groups) {
        foreach ( $groups as $group ) {
            $this->add_role_to_admin($admin, $groups);
        }
        return true;
    }

    public function remove_roles_from_admin($admin, $groups) {
        foreach ( $groups as $group ) {
            $role_name = $this->get_role_name($group);
            if ($this->role_service->role_exists($role_name)) {
                $this->role_service->remove_role_from_admin ($role_name, $admin);
            }
        }
        return true;
    }

    public function add_role_to_admin($admin,$group){
        $role_name = $this->get_role_name($group);
        if ($this->role_exists($role_name)) {
            $user = new WP_User( $admin->get_user_id() );
            return $user->add_role( $role );
        }
        return null;
    }

    public function remove_role_from_admin($admin,$group){
        $role_name = $this->get_role_name($group);
        if ($this->role_exists($role_name)) {
            $user = new WP_User( $admin->get_user_id() );
            return $user->remove_role( $role );
        }
        return null;
    }

    public function get_role_name($group) {
        return 'cb-group-'.$group->get_post_id();
    } 

    public function get_role($group) {
        return get_role($this->get_role_name($group));
    }

    private function role_exists( $role ) {
        return $GLOBALS['wp_roles']->is_role( $role );
    }

}