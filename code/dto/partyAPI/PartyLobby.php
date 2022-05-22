<?php

namespace {
    class PartyLobbyDTO extends DTO {

        public $userList = [];

        public $gameTypes = [];
    }
}

namespace PartyLobbyDTO {
    class UserDTO {

        public $nickname;

        public $points = 0;

        public $admin = false;

        public $id = null;
    }

    class GameTypeDTO {

        public $name;

        public $roles = [];
    }

    class RoleDTO {
        public $categorie;

        public $default;

        public $name;
    }
}