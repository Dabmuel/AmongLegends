<?php

class PartyDAO extends IdentifierDAO {

    function __construct()
    {
        parent::__construct();
        $this->tableName = "party";
    }

    public function create($partyDTO) {
    }

    public function update($partyDTO) {
        
    }
}

new PartyDAO();