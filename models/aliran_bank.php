<?php

class Aliran_Bank_Model extends Common_Model {
    
    public function ReadSemuaAliranBank( $limit = 100) {
        $query = "SELECT * FROM aliran_bank "
                . "ORDER BY id DESC "
                . "LIMIT $limit";
        return $this->db->executeQuery($query);
    }
    
    public function ReadSummaryAliranBank(){
        $query = "SELECT "
                . "sum(IF(kategori=1, jumlah, 0)) as masuk, "
                . "sum(IF(kategori=2, jumlah, 0)) as keluar "
                . "FROM aliran_bank ";
        return $this->db->executeQuery($query,'single');
    }
    
    public function ReadAliranBank(){
        $query = "SELECT "
                . "(sum(if(kategori=1,jumlah,0)) - sum(if(kategori=2, jumlah,0))) as dalam_bank "
                . "FROM aliran_bank ";
        return $this->db->executeQuery($query,'single');
    }

    public function ReadCawanganDetail($id) {
        $query = "SELECT * FROM cawangan WHERE id='" . (int) $id . "'";
        return $this->db->executeQuery($query, 'single');
    }

    public function CreateAliranBank($data) {
        $query = "INSERT INTO aliran_bank (perkara,jumlah,kategori,ref_at_id) VALUE ("
                . "'" . $this->db->escape($data['perkara']) . "', "
                . "'" . $this->db->escape($data['jumlah']) . "', "
                . "'" . $this->db->escape($data['kategori']) . "', "
                . "'" . $this->db->escape($data['ref_at_id']) . "'"
                . ")";
        return $this->db->executeQuery($query);
    }
}
