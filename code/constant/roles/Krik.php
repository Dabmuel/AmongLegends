<?php

class Krik extends Role implements RoleCalculation {

    public function __construct()
    {
        parent::__construct();

        $this->gameTypes = [
            "Normal",
            "ARAM"
        ];
    }

    /**
     * Krik aka Escroc
     *
     * Il doit se faire voter SussyBaka.
     * Mais le tout surtout sans perdre !
     */
    public function calculPoints(EndStatDTO $endStatDTO, int $gameSessionId) {
        $points = 0;

        if ($endStatDTO->win) {
            $points += 4;
        } else {
            $points -= 10;
        }

        $endVoteService = SingletonRegistry::$registry['EndVoteService'];

        $endVotesOnHim = $endVoteService->getAllByVotedGS($gameSessionId);
        foreach ($endVotesOnHim as $endVote) {
            if ($endVote->role == "SussyBaka") {
                $points += 2;
            }
        }

        $points += $this->getVotePoints($gameSessionId);

        return $points;
    }

    public function getRoleAddInfos($sessionId, $gameId) {
        return null;
    }
}

new Krik();