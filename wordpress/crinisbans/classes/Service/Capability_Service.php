<?php
namespace crinis\cb\Service;

class Capability_Service {

    private $capabilities;

    public function __construct(){
        $this->capabilities = [
            'cb_admin',
            'cb_ban',
            'cb_group',
            'cb_reason',
            'cb_server',
            'cb_server_group',
            'cb_delete'
        ];
    }

    public function get_role_capabilities($role){
        $final_capabilities = [];
        foreach ($this->capabilities as $capability) {
            if ( $role->capabilities[$capability]  === 1 ) {
                $final_capabilities[$capability] = 1;
            } else {
                $final_capabilities[$capability] = 0;
            }
        }
        return $final_capabilities;
    }

    public function set_role_capabilities($role, $capabilities){
        foreach ($this->capabilities as $capability) {
            if ( $capabilities[$capability] === 1 ) {
                add_cap($role, $capability);
            } else {
                remove_cap($role, $capability);
            }
        }
    }

    public function has_enabled_role_capability($role, $capability){
        if (!in_array($capability, $capabilities)) {
            return false;
        }
        return $role->capabilities[$capability] === 1 ? true : false;
    }

}