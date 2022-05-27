<?php

class GameSessionService extends IdentifierService {

    public function __construct(){
        parent::__construct();

        $this->DAO = SingletonRegistry::$registry["GameSessionDAO"];
    }

    public function getBySessionAndGame($sessionId, $gameId) {
        return $this->DAO->getBySessionAndGame($sessionId, $gameId);
    }

    public function getAllByGame($gameId) {
        return $this->DAO->getAllByGame($gameId);
    }

    public function generateGameSessions($sessionList, $roleList, $gameId) {
        $rolesCount = 0;
        foreach ($roleList as $count) {
            $rolesCount += $count;
        }
        if (count($sessionList) > $rolesCount) {
            throw new Exception();
        }

        $returner = [];

        $impostorRoles = [];
        foreach ($roleList as $role => $count) {
            $roleObject = SingletonRegistry::$registry["Role::".$role];
            if ($roleObject->categorie === "Imposteur") {
                $impostorRoles[$role] = $count;
            }
        }
        if (count($impostorRoles) > 0) {
            $impostorRolesCount = 0;
            foreach ($impostorRoles as $count) {
                $impostorRolesCount += $count;
            }

            $roleI = rand(1, $impostorRolesCount);
            $roleV = '';
            $counting = 0;
            foreach ($impostorRoles as $role => $count) {
                $counting += $count;
                if ($roleI <= $counting) {
                    $roleV = $role;
                    break;
                }
            }

            $sessionI = rand(0, count($sessionList) -1);
            $session = $sessionList[$sessionI];

            $newGameSession = new GameSessionDTO();

            $newGameSession->sessionId = $session->identifier;
            $newGameSession->gameId = $gameId;
            $newGameSession->nickname = $session->nickname;
            $newGameSession->role = $roleV;
            $newGameSession->roleAddInfos = SingletonRegistry::$registry['Role::'.$roleV]->getRoleAddInfos($session->identifier, $gameId);
            $newGameSession->points = 0;
            $newGameSession->voted = false;

            $rolesCount--;
            $roleList[$roleV]--;

            unset($sessionList[$sessionI]);

            $newGameSession = $this->create($newGameSession);

            $returner[] = $newGameSession;
        }



        foreach ($sessionList as $session) {
            $newGameSession = new GameSessionDTO();

            $roleI = rand(1, $rolesCount);
            $roleV = '';
            $counting = 0;
            foreach ($roleList as $role => $count) {
                $counting += $count;
                if ($roleI <= $counting) {
                    $roleV = $role;
                    break;
                }
            }

            $newGameSession->sessionId = $session->identifier;
            $newGameSession->gameId = $gameId;
            $newGameSession->nickname = $session->nickname;
            $newGameSession->role = $roleV;
            $newGameSession->roleAddInfos = SingletonRegistry::$registry['Role::'.$roleV]->getRoleAddInfos($session->identifier, $gameId);
            $newGameSession->points = 0;
            $newGameSession->voted = false;

            $rolesCount--;
            $roleList[$roleV]--;

            $newGameSession = $this->create($newGameSession);

            $returner[] = $newGameSession;
        }

        return $returner;
    }
}

new GameSessionService();