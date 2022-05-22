<?php

class Roles extends Singleton {

    public function __construct()
    {
        parent::__construct();
    }

    public $rolesEnum = [];

    public function getRolesObject($roleList = null) {
        if ($roleList == null) {
            $roleList = $this->rolesEnum;
        }

        $returner = [];

        foreach ($roleList as $role) {
            $returner[] = SingletonRegistry::$registry["Role::".$role];
        }

        return $returner;
    }
}

new Roles();

