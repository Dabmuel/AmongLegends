<?php

class GameDAO extends IdentifierDAO {

    function __construct()
    {
        parent::__construct();
        $this->tableName = "game";
    }

    public function create($gameDTO) {
        try {
            $sql = $this->db->prepare(
                'INSERT INTO '.$this->tableName.' (PARTY_ID , STATUT , TYPE, ROLES) 
                VALUES (:partyId , :statut , :type, :roles)'
            );
            $sql->execute([
                'partyId' => $gameDTO->partyId,
                'statut' => $gameDTO->statut,
                'type' => $gameDTO->type,
                'roles' => json_encode($gameDTO->roles)
            ]);
            $gameDTO->identifier = $this->db->lastInsertId();
            return $gameDTO;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    public function update($gameDTO) {
        try {
            $sql = $this->db->prepare(
                'UPDATE '.$this->tableName.' SET
                PARTY_ID = :partyId , 
                STATUT = :statut , 
                TYPE = :type , 
                ROLES = :roles 
                WHERE ID = :id'
            );
            $sql->execute([
                'partyId' => $gameDTO->partyId,
                'statut' => $gameDTO->statut,
                'type' => $gameDTO->type,
                'roles' => json_encode($gameDTO->roles),
                'id' => $gameDTO->identifier
            ]);
            return $gameDTO;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    protected function fetch($data) {
        $gameDTO = new GameDTO();
        
        $gameDTO->identifier = $data['ID'];
        $gameDTO->partyId = $data['PARTY_ID'];
        $gameDTO->statut = $data['STATUT'];
        $gameDTO->type = $data['TYPE'];
        $gameDTO->roles = json_decode($data['ROLES']);

        return $gameDTO;
    }
}

new GameDAO();