<?php

class SussyBaka extends Role implements RoleCalculation {

    public function __construct()
    {
        parent::__construct();

        $this->categorie = "Imposteur";
    }

    /**
     * SussyBaka aka Imposteur
     *
     * Doit perdre la partie.
     * Mais surtout sans se faire choper.
     */
    public function calculPoints(EndStatDTO $endStatDTO, int $gameSessionId) {
        $points = 0;

        if ($endStatDTO->win) {
            $points -= 5;
        } else {
            $points += 10;
        }

        $endVoteService = SingletonRegistry::$registry['EndVoteService'];

        $endVotesOnHim = $endVoteService->getAllByVotedGS($gameSessionId);
        foreach ($endVotesOnHim as $endVote) {
            if ($endVote->role == get_class($this)) {
                $points -= 2;
            }
        }

        $points += $this->getVotePoints($gameSessionId);

        return $points;
    }

    public function getRoleAddInfos($sessionId) {
        return null;
    }
}

new SussyBaka();