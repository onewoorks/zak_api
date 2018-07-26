<?php

class Bank_Model extends Common_Model {

    public function ReadSemuaBank($limit = 100) {
        $query = "SELECT * FROM bank_account "
                . "LIMIT $limit";
        return $this->db->executeQuery($query);
    }

 

}
