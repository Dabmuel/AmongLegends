<?php

class EndVoteDAO extends IdentifierDAO {

    function __construct()
    {
        parent::__construct();
        $this->tableName = "end_vote";
    }

    public function create($endVoteDTO) {
        try {
            $sql = $this->db->prepare(
                'INSERT INTO '.$this->tableName.' (ROLE , VOTING_GS_ID , VOTED_GS_ID) 
                VALUES (:role , :votingGSId , :votedGSId)'
            );
            $sql->execute([
                'role' => $endVoteDTO->role,
                'votingGSId' => $endVoteDTO->votingGSId,
                'votedGSId' => $endVoteDTO->votedGSId
            ]);
            $endVoteDTO->identifier = $this->db->lastInsertId();
            return $endVoteDTO;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    public function update($endVoteDTO) {
        try {
            $sql = $this->db->prepare(
                'UPDATE '.$this->tableName.' SET
                ROLE = :role , 
                VOTING_GS_ID = :votingGSId , 
                VOTED_GS_ID = :votedGSId 
                WHERE ID = :id'
            );
            $sql->execute([
                'role' => $endVoteDTO->role,
                'votingGSId' => $endVoteDTO->votingGSId,
                'votedGSId' => $endVoteDTO->votedGSId,
                'id' => $endVoteDTO->identifier
            ]);
            return $endVoteDTO;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    public function getAllByVotingGS($gsId) {
        try {
            $sql = $this->db->prepare('SELECT * FROM '.$this->tableName.' WHERE VOTING_GS_ID = :gsId');
            $sql->execute([
                'gsId' => $gsId
            ]);
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $returner = [];

            foreach ($data as $endVote) {
                $returner[] = $this->fetch($endVote);
            }

            return $returner;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    public function getAllByVotedGS($gsId) {
        try {
            $sql = $this->db->prepare('SELECT * FROM '.$this->tableName.' WHERE VOTED_GS_ID = :gsId');
            $sql->execute([
                'gsId' => $gsId
            ]);
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $returner = [];

            foreach ($data as $endVote) {
                $returner[] = $this->fetch($endVote);
            }

            return $returner;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    protected function fetch($data) {
        $endVoteDTO = new EndVoteDTO();

        $endVoteDTO->identifier = $data["ID"];
        $endVoteDTO->votingGSId = $data["VOTING_GS_ID"];
        $endVoteDTO->votedGSId = $data["VOTED_GS_ID"];
        $endVoteDTO->role = $data["ROLE"];

        return $endVoteDTO;
    }
}

new EndVoteDAO();