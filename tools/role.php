<?php
class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }


    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }
}

?>