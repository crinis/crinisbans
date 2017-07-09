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

    public function get_capabilities($role){
        $capabilities = [];
        foreach ($this->capabilities as $capability) {
            if ( $this->has_capability($role, $capability) ) {
                $capabilities[$capability] = '1';
            } else {
                $capabilities[$capability] = '0';
            }
        }
        return $capabilities;
    }

    public function set_capabilities($role, $capabilities){
        foreach ($this->capabilities as $capability) {
            if ( '1' === $capabilities[$capability] ) {
                if (!$this->has_capability($role, $capability)) {
                    $role->add_cap($capability);
                }
            } else if($this->has_capability($role, $capability)) {
                $role->remove_cap($capability);
            }
        }
    }

    public function has_capability($role, $capability){
        if (!in_array($capability, $this->capabilities)) {
            return false;
        }
        return $role->capabilities[$capability];
    }

}