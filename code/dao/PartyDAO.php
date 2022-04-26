<?php

class PartyDAO extends IdentifierDAO {

    function __construct()
    {
        parent::__construct();
        $this->tableName = "party";
    }

    public function create($partyDTO) {
        try {
            $sql = $this->db->prepare(
                'INSERT INTO '.$this->tableName.' (ACTIVE , CODE , DYING_DATE , ACTIVE_GAME_ID) 
                VALUES (:active , :code , :dyingDate, :activeGameId)'
            );
            $sql->bindParam('active', $partyDTO->active, PDO::PARAM_BOOL);
            $sql->bindParam('code', $partyDTO->code);
            $sql->bindParam('dyingDate', $partyDTO->dyingDate);
            $sql->bindParam('activeGameId', $partyDTO->activeGameId);
            $sql->execute();
            $partyDTO->identifier = $this->db->lastInsertId();
            return $partyDTO;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    public function update($partyDTO) {
        try {
            $sql = $this->db->prepare(
                'UPDATE '.$this->tableName.' SET
                ACTIVE = :active , 
                CODE = :code , 
                DYING_DATE = :dyingDate ,
                ACTIVE_GAME_ID = :activeGameId 
                WHERE ID = :id'
            );
            $sql->execute([
                'active' => $partyDTO->active,
                'code' => $partyDTO->code,
                'dyingDate' => $partyDTO->dyingDate,
                'activeGameId' => $partyDTO->activeGameId,
                'id' => $partyDTO->identifier
            ]);
            return $partyDTO;
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    protected function fetch($data) {
        $partyDTO = new PartyDTO();

        $partyDTO->identifier = $data['ID'];
        $partyDTO->active = $data['ACTIVE'];
        $partyDTO->code = $data['CODE'];
        $partyDTO->dyingDate = $data['DYING_DATE'];
        $partyDTO->activeGameId = $data['ACTIVE_GAME_ID'];

        return $partyDTO;
    }

    public function getByCode($code) {
        try {
            $sql = $this->db->prepare('SELECT * FROM '.$this->tableName.' WHERE CODE = :code');
            $sql->execute([
                'code' => $code
            ]);
            $data = $sql->fetch(PDO::FETCH_ASSOC);

            if (!$data["ID"]) {
                return null;
            }
            return $this->fetch($data);
        } catch(PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }
}

new PartyDAO();