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
        if ($_POST["nickname"] && !$this->errorMessage) {
            if ($this->validNickname($_POST["nickname"])) {
                $party = null;
                if ($_GET["party"]) {
                    $party = $this->partyService->getPartyActiveByCode($_GET["party"]);
                } else {
                    $party = $this->partyService->createParty();
                }
                error_log($party->code);

                if ($party == null) {
                    $this->setErrorMessage("NO_PARTY_FROM_CODE");
                } else {
                    if ($this->notDuplicateNickname($_POST["nickname"], $party)) {
                        $session = $this->sessionService->joinParty($_POST["nickname"], $party);
                    } else {
                        $this->setErrorMessage("NICKNAME_DUPLICATE");
                    }
                }
            } else {
                $this->setErrorMessage("NICKNAME_UNVALID");
            }

            if (!$this->errorMessage) {
                header("Location: ".Config::$baseUrl."/party");
            }
        }
        if ($_GET["error"]) {
            $this->setErrorMessage($_GET["error"]);
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
        return (strlen($nickname) < 30 && strlen($nickname) > 2);
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

    private function setErrorMessage($errorCode) {
        switch($errorCode) {
            case("PARTY_IN_GAME"):
                $this->errorMessage = "Erreur: Une partie est déjà en cours.";
                break;
            case("PARTY_FULL"):
                $this->errorMessage = "Erreur: Le salon est complet.";
                break;
            case("NICKNAME_UNVALID"):
                $this->errorMessage = "Erreur: Pseudo invalide.";
                break;
            case("NICKNAME_DUPLICATE"):
                $this->errorMessage = "Erreur: Pseudo déjà existant.";
                break;
            case("NO_PARTY_FROM_CODE"):
                $this->errorMessage = "Erreur: Le code ne correspond pas à un salon actif.";
                break;
            case("NO_PARTY"):
                $this->errorMessage = "Erreur: Le salon a expiré.";
                break;
            case("NO_SESSION"):
                $this->errorMessage = "Erreur: Vous n'êtes pas connecté à un salon.";
                break;
            case(""):
                break;
            default:
                $this->errorMessage = "Erreur quelconque. Je ne sais pas si tu avais remarqué mais il y a différents types de profs.";
                break;
        }
    }
}

new LoginController();