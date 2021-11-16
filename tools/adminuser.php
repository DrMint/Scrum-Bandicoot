<?php
class AdminUser extends User
{
    private $roles;

    public function __construct() {
        parent::__construct();
    }

    // override User method
    public static function getByUsername($username) {

        if (exist($username)) {
            $admin = new AdminUser();
            $admin->username = $username;
            $admin->password = $password;
            return $admin;
        } else {
            return false;
        }
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }
}

?>