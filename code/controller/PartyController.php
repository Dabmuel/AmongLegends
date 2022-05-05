<?php

class PartyController extends Controller{

    private SessionManager $sessionManager;

    function __construct() {
        parent::__construct();

        $this->sessionManager = SingletonRegistry::$registry['SessionManager'];
    }

    public function process() {
        if ($this->sessionManager->currentSessionDTO && !$this->sessionManager->errorCode) {
            $this->front();
        } else {
            header("Location: ".Config::$baseUrl."/login".($this->sessionManager->errorCode ? "?error=".$this->sessionManager->errorCode : ""));
        }

    }

    private function front() {
        $base = SingletonRegistry::$registry["Request"]->base;
        include($base.'code/front/header.php');
        include($base.'code/front/page/party.php');
        include($base.'code/front/footer.php');
    }
}

new PartyController();