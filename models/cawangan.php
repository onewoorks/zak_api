<?php

class Cawangan_Model extends Common_Model {

    public function ReadSemuaCawangan() {
        $query = "SELECT * FROM cawangan";
        return $this->db->executeQuery($query);
    }
    
    public function ReadSemuaCawanganLama() {
        $query = "SELECT * FROM cawangan_lama "
                . "WHERE status=0 "
                . "ORDER BY nama_cawangan ASC";
        return $this->db->executeQuery($query);
    }

    public function ReadCawanganDetail($id) {
        $query = "SELECT * FROM cawangan WHERE id='" . (int) $id . "'";
        return $this->db->executeQuery($query, 'single');
    }

    public function CreateCawangan($data) {
        $query = "INSERT INTO cawangan (nama_cawangan,alamat,no_telefon,no_gst) VALUE ("
                . "'" . $this->db->escape($data['nama']) . "', "
                . "'" . $this->db->escape($data['alamat']) . "', "
                . "'" . $this->db->escape($data['notelefon']) . "', "
                . "'" . $this->db->escape($data['nogst']) . "'"
                . ")";
        return $this->db->executeQuery($query);
    }

    public function UpdateCawangan($data) {
        $query = "UPDATE cawangan SET "
                . "nama_cawangan='" . $this->db->escape($data['nama']) . "', "
                . "alamat='" . $this->db->escape($data['alamat']) . "',"
                . "no_telefon='" . $this->db->escape($data['notelefon']) . "', "
                . "no_gst='" . $this->db->escape($data['nogst']) . "' "
                . "WHERE id='".(int) $data['id']."'";
        return $this->db->executeQuery($query);
    }

}
