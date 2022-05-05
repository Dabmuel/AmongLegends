<?php

class SessionManager extends Singleton {

    private SessionService $sessionService;
    private PartyService $partyService;

    public $errorCode = "";

    function __construct()
    {
        parent::__construct();
    }

    function init() {
        $this->sessionService = SingletonRegistry::$registry['SessionService'];
        $this->partyService = SingletonRegistry::$registry['PartyService'];
    }

    public $currentSessionDTO = null;

    public function createSession($sessionDTO) {
        $_SESSION['token'] = $sessionDTO->token;
        $_SESSION['nickname'] = $sessionDTO->nickname;
        $this->currentSessionDTO = $sessionDTO;
    }

    public function initSession() {
        session_start();
        if ($_SESSION['token']) {
            $this->currentSessionDTO = $this->sessionService->getByToken($_SESSION['token']);
            if (!$this->currentSessionDTO ) {
                $this->errorCode = "NO_SESSION";
            } else {
                $currentParty = $this->partyService->get($this->currentSessionDTO->partyId);
                if (!$currentParty) {
                    $this->errorCode = "NO_PARTY";
                }
            }

            return $this->currentSessionDTO;
        }
    }

    public function deleteSession() {
        $_SESSION['token'] = null;
        $_SESSION['nickname'] = null;
        session_destroy();
    }
}

new SessionManager();