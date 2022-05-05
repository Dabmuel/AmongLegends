<?php

class PartyVoteAPIController extends Controller {

    private SessionManager $sessionManager;
    private PartyService $partyService;
    private GameService $gameService;
    private GameSessionService $gameSessionService;
    private EndVoteService $endVoteService;
    private EndStatService $endStatService;

    private $error = false;
    private $rolesEnum;
    private $partyStatutEnum;

    function __construct() {
        parent::__construct();

        $this->sessionManager = SingletonRegistry::$registry["SessionManager"];
        $this->partyService = SingletonRegistry::$registry["PartyService"];
        $this->gameService = SingletonRegistry::$registry["GameService"];
        $this->gameSessionService = SingletonRegistry::$registry["GameSessionService"];
        $this->endVoteService = SingletonRegistry::$registry["EndVoteService"];
        $this->endStatService = SingletonRegistry::$registry["EndStatService"];

        $this->rolesEnum = SingletonRegistry::$registry['Roles']->rolesEnum;
        $this->partyStatutEnum = SingletonRegistry::$registry["PartyStatut"]->partyStatutEnum;
    }

    public function process() {
        $currentSessionDTO = $this->sessionManager->currentSessionDTO;

        if($currentSessionDTO){
            $currentPartyDTO = $this->partyService->get($currentSessionDTO->partyId);

            if($currentPartyDTO) {

                $currentGameDTO = null;

                if ($currentPartyDTO->activeGameId) {
                    $currentGameDTO = $this->gameService->get($currentPartyDTO->activeGameId);
                }
                if ($currentGameDTO == null
                    || $currentGameDTO->statut !== SingletonRegistry::$registry['PartyStatut']->partyStatutEnum[3])//Voting
                {
                    $this->error('', 'Party sous le mauvais statut.');
                    return;
                }
                $currentGameSessionDTO = $this->gameSessionService->getBySessionAndGame($currentSessionDTO->identifier, $currentGameDTO->identifier);
                if ($currentGameSessionDTO == null) {
                    $this->error('', 'Session de jeu introuvable. Qu\'est ce que t\'as foutu ?');
                    return;
                }
                if (count($this->endVoteService->getAllByVotingGS($currentGameSessionDTO->identifier)) >= 4) {
                    $this->ok();//Déjà voté
                    return;
                }

                $voteIndex = 1;
                $voteList = [];
                $endVoteDTOList = [];
                while ($_POST['vote-'.$voteIndex]) {
                    try {
                        $vote = json_decode($_POST['vote-'.$voteIndex]);
                        if ($vote == null) {
                            $this->error('', 'JSON parsing des votes a échoué.');
                            return;
                        }
                        $voteList[] = $vote;
                    } catch (Exception $e) {
                        $this->error('', 'JSON parsing des votes a échoué.');
                        return;
                    }
                    $voteIndex++;
                }
                if (count($voteList) != 4) {
                    $this->error('', 'Pas le bon nombre de votes');
                    return;
                }
                foreach ($voteList as $vote) {
                    $voteAsArray = get_object_vars($vote);
                    if (count($voteAsArray) !== 1) {
                        $this->error('', 'Vote invalide.');
                        return;
                    }
                    foreach ($voteAsArray as $gs_id => $role) {
                        if (!in_array($role, $this->rolesEnum)) {
                            $this->error('', 'Role voté invalide.');
                            return;
                        }
                        $gameSession = $this->gameSessionService->get($gs_id);
                        if ($gameSession == null
                            || $gameSession->gameId != $currentGameDTO->identifier
                            || $gameSession->sessionId == $currentSessionDTO->identifier) {
                            $this->error('', 'Personne votée invalide.');
                            return;
                        };
                        $newEndVoteDTO = new EndVoteDTO();

                        $newEndVoteDTO->votingGSId = $currentGameSessionDTO->identifier;
                        $newEndVoteDTO->votedGSId = $gameSession->identifier;
                        $newEndVoteDTO->role = $role;

                        $endVoteDTOList[] = $newEndVoteDTO;
                    }
                }

                foreach ($endVoteDTOList as $endVoteDTO) {
                    $this->endVoteService->create($endVoteDTO);
                }

                if(!$this->error) {
                    $this->ok();
                }

                $votedCount = 0;
                $gameSessions = $this->gameSessionService->getAllByGame($currentGameDTO->identifier);
                foreach ($gameSessions as $gameSession) {
                    if(count($this->endVoteService->getAllByVotingGS($gameSession->identifier)) >= 4) {
                        $votedCount++;
                    }
                }
                if ($votedCount == 5) {
                    $this->GameEnded($currentGameDTO, $gameSessions);
                }

            } else {

                $this->error("NO_PARTY");

            }

        } elseif ($_SESSION['token']) {

            $this->error("NO_ACTIVE_SESSION");

        } else {

            $this->error("NO_SESSION");

        }
    }

    private function GameEnded(GameDTO $currentGameDTO, $gameSessionDTOS ) {
        $endStatDTO = $this->endStatService->get($currentGameDTO->identifier);
        foreach ($gameSessionDTOS as $gameSessionDTO) {
            $roleConstant = SingletonRegistry::$registry['Role::'.$gameSessionDTO->role];
            $gameSessionDTO->points = $roleConstant->calculPoints($endStatDTO, $gameSessionDTO->identifier);
            $this->gameSessionService->update($gameSessionDTO);
        }
        $currentGameDTO->statut = $this->partyStatutEnum[4];//EndGame
        $this->gameService->update($currentGameDTO);
    }

    private function ok() {
        header("Location: ".Config::$baseUrl."/party");
    }

    private function error($error_code = '', $error_msg = '') {
        if($this->error) {
            return;
        }
        switch($error_code) {
            case("NO_PARTY"):
                header('HTTP/1.0 403 Forbidden');
                echo 'Salon expiré';
                break;
            case("NO_SESSION"):
                header('HTTP/1.0 401 Unauthorized');
                echo 'Aucune session trouvée';
                break;
            case("NO_ACTIVE_SESSION"):
                header('HTTP/1.0 403 Forbidden');
                echo 'Session morte';
                break;
            default:
                header('HTTP/1.0 400 Bad Request');
                echo 'Erreur: '.$error_msg;
                break;
        }
        $this->error = true;
    }
}

new PartyVoteAPIController();