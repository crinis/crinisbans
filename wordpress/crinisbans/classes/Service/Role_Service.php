<?php
namespace crinis\cb\Service;

class Role_Service {

    public function add_role(Group $group) {
        $role = $this->get_role_name($group);
        return add_role( $role, $role );
    }

    public function remove_role(Group $group) {
        $role = $this->get_role_name($group);
        return remove_role( $role );
    }

    public function get_role(Group $group) {
        return 'cb-group-'.$group->get_post_id();
    }

    public function has_role( $group ) {
        $role = $this->get_role($group);
        return $GLOBALS['wp_roles']->is_role( $role );
    }

    public function add_role_to_user($admin,$role){
        $user = new WP_User( $admin->get_user_id() );
        return $user->add_role( $role );
    }

    public function remove_role_from_user($admin,$role){
        $user = new WP_User( $admin->get_user_id() );
        return $user->remove_role( $role );
    }

    public function has_user_role($admin,$role){
        $user = new WP_User( $admin->get_user_id() );
        return in_array( $role, (array) $user->roles );
    }

}