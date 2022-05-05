<?php

class LoginController extends Controller {

    private PartyService $partyService;
    private SessionService $sessionService;

    public string $errorMessage = "";

    function __construct() {
        parent::__construct();

        $this->partyService = SingletonRegistry::$registry["PartyService"];
        $this->sessionService = SingletonRegistry::$registry["SessionService"];
    }

    public function process() {
        if ($_GET["error"]) {
            $this->errorMessage($_GET["error"]);
        }
        if ($_POST["nickname"] && $this->validNickname($_POST["nickname"])) {
            $party = null;
            if ($_GET["party"]) {
                $party = $this->partyService->getPartyActiveByCode($_GET["party"]);
            }
            if ($party == null) {
                $party = $this->partyService->createParty();
            }

            if ($this->notDuplicateNickname($_POST["nickname"], $party)) {
                $session = $this->sessionService->joinParty($_POST["nickname"], $party);

                header("Location: ".Config::$baseUrl."/party");
            }
        }

        $this->front();
    }

    private function front() {
        $base = SingletonRegistry::$registry["Request"]->base;
        include($base.'code/front/header.php');
        include($base.'code/front/page/login.php');
        include($base.'code/front/footer.php');
    }

    private function validNickname($nickname) {
        return (strlen($nickname) < 30);
    }

    private function notDuplicateNickname($nickname, PartyDTO $party) {
        $valid = true;

        $partySessions = $this->sessionService->getPartySessions($party->identifier);
        foreach ($partySessions as $partySession) {
            if ($nickname == $partySession->nickname) {
                $valid = false;
                break;
            }
        }
        return $valid;
    }

    private function getErrorMessage($errorCode) {
        switch($errorCode) {
            case(""):
                break;
            default:
                $this->errorMessage = 'Erreur quelconque. Je ne sais pas si tu avais remarqué mais il y a différents types de profs.';
                break;
        }
    }
}

new LoginController();