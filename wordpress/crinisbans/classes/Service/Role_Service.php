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
}