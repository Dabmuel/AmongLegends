<?php

class Role extends Singleton {

    public $name;

    public $categorie = "Normal";

    public $addInfos;

    public function __construct() {
        $this->name = get_class($this);

        SingletonRegistry::$registry["Roles"]->rolesEnum[] = $this->name;

        parent::__construct('Role::'.$this->name);
    }

    protected function getVotePoints($gameSessionId) {
        $points = 0;

        $endVoteService = SingletonRegistry::$registry['EndVoteService'];
        $gameSessionService = SingletonRegistry::$registry['GameSessionService'];

        $endVotes = $endVoteService->getAllByVotingGS($gameSessionId);
        foreach ($endVotes as $endVote) {
            $gameSession = $gameSessionService->get($endVote->votedGSId);
            if ($gameSession->role === $endVote->role) {
                $points += 2;
            }
        }

        return $points;
    }
}