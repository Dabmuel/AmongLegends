<?php

class RoleCategorie extends Singleton {

    public function __construct()
    {
        parent::__construct();
    }

    public $categorieEnum = [
        "Normal",
        "Imposteur"
    ];
}

new RoleCategorie();