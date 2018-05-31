<?php

class Aliran_Bank_Model extends Common_Model {
    
    public function ReadSemuaAliranBank() {
        $query = "SELECT * FROM aliran_bank";
        return $this->db->executeQuery($query);
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
