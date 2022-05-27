<?php

class Ratio extends Role implements RoleCalculation {

    public function __construct()
    {
        parent::__construct();

        $this->gameTypes = [
            "Normal",
            "ARAM"
        ];
    }

    /**
     * Ratio aka Serpentin
     *
     * Il est là pour le gaming.
     * Doit gagner la partie.
     * Mais surtout doit avoir le plus de morts, de kills et de dégats.
     */
    public function calculPoints(EndStatDTO $endStatDTO, int $gameSessionId) {
        $points = 0;

        if ($endStatDTO->win) {
            $points += 5;
        } else {
            $points -= 5;
        }

        if ($endStatDTO->mostDmg_GameSessionId == $gameSessionId) {
            $points += 2;
        }
        if ($endStatDTO->mostDeath_GameSessionId == $gameSessionId) {
            $points += 2;
        }
        if ($endStatDTO->mostKill_GameSessionId == $gameSessionId) {
            $points += 2;
        }

        $points += $this->getVotePoints($gameSessionId);

        return $points;
    }

    public function getRoleAddInfos($sessionId, $gameId) {
        return null;
    }
}

new Ratio();