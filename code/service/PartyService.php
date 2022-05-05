<?php

class PartyService extends IdentifierService {

    function __construct(){
        parent::__construct();

        $this->DAO = SingletonRegistry::$registry["PartyDAO"];
    }

    public function update($DTO) {
        $DTO->dyingDate = new DateTime();
        $DTO->dyingDate->add(DateInterval::createFromDateString('1 day'));

        return $this->DAO->update($DTO);
    }

    public function get($identifier) {
        $party = $this->DAO->get($identifier);
        if ($this->isActive($party)) {
            return $party;
        }
        return null;
    }

    public function getPartyActiveByCode($code) {
        $party = $this->DAO->getByCode($code);
        if ($this->isActive($party)) {
            return $party;
        }
        return null;
    }

    public function createParty() {
        $party = new PartyDTO();
        
        $party->code = $this->newToken();
        $party->dyingDate = new DateTime();
        $party->dyingDate->add(DateInterval::createFromDateString('1 day'));

        $this->DAO->create($party);

        return $party;
    }

    private function isActive($party) {
        if ($party && $party->dyingDate->diff(new DateTime())->invert == 1) {
            return true;
        } else {
            return false;
        }
    }

    private function newToken() {
        do {
            $code = '';
            for ($i = 0; $i < 3; $i++) {
                $code .= $this->codeCarac[rand(0, count($this->codeCarac)-1)];
            }
        } while($this->getPartyActiveByCode($code) == true);

        return $code;
    }

    private $codeCarac = [ //62 caractères
        '0','1','2','3','4','5','6','7','8','9',
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ];
}

new PartyService();