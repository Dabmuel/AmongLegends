<?php

class GameType extends Singleton {

    public function __construct()
    {
        parent::__construct();
    }

    public $gameTypeEnum = [
        "Normal"
    ];
}

new GameType();