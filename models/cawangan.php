<?php

class Cawangan_Model extends Common_Model {

    public function ReadSemuaCawangan() {
        $query = "SELECT * FROM cawangan";
        return $this->db->executeQuery($query);
    }

    public function ReadSemuaCawanganLama() {
        $query = "SELECT *, "
            . "alamat, "
            . "no_telefon "
            . "FROM cawangan_lama "
            . "WHERE status=0 "
            . "ORDER BY nama_cawangan ASC";
        return $this->db->executeQuery($query);
    }

    public function ReadSemuaCawanganHead() {
        $query = "SELECT * FROM cawangan_lama "
            . "WHERE status=0 "
            . "ORDER BY cawangan_head ASC";
        return $this->db->executeQuery($query);
    }

    public function ReadCawanganDetail($id) {
        $query = "SELECT * FROM cawangan WHERE id='" . (int) $id . "'";
        return $this->db->executeQuery($query, 'single');
    }
    public function ReadCawanganLamaDetail($id) {
        $query = "SELECT * FROM cawangan_lama WHERE id='" . (int) $id . "'";
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

    public function CreateCawanganLama($data) {
        $query = "INSERT INTO cawangan_lama (cawangan_head,nama_cawangan, alamat, no_telefon, usr_id, status) VALUE ("
        . "'" . $this->db->escape($data['nama']) . "', "
        . "'" . $this->db->escape($data['nama']) . "', "
        . "'" . $this->db->escape($data['alamat']) . "', "
        . "'" . $this->db->escape($data['notelefon']) . "', "
        . "'" . (int) $data['profile'] . "', "
            . "'0'"
            . ")";
        return $this->db->executeQuery($query);
    }

    public function UpdateCawangan($data) {
        $query = "UPDATE cawangan SET "
        . "nama_cawangan='" . $this->db->escape($data['nama']) . "', "
        . "alamat='" . $this->db->escape($data['alamat']) . "',"
        . "no_telefon='" . $this->db->escape($data['notelefon']) . "', "
        . "no_gst='" . $this->db->escape($data['nogst']) . "' "
        . "WHERE id='" . (int) $data['id'] . "'";
        return $this->db->executeQuery($query);
    }

    public function UpdateCawanganLama($data) {
        $query = "UPDATE cawangan_lama SET "
        . "cawangan_head='" . $this->db->escape($data['nama']) . "', "
        . "nama_cawangan='" . $this->db->escape($data['nama']) . "', "
        . "alamat='" . $this->db->escape($data['alamat']) . "',"
        . "no_telefon='" . $this->db->escape($data['notelefon']) . "', "
        . "usr_id='" . $this->db->escape($data['profile']) . "' "
        . "WHERE id='" . (int) $data['id'] . "'";
        return $this->db->executeQuery($query);
    }

    public function ReadSemuaKakitangan() {
        $query = "SELECT a.*, c.nama_cawangan as nama_cawangan "
            . "FROM ahli a "
            . "LEFT JOIN cawangan_lama c ON c.id=a.caw_id "
            . "WHERE a.stf_stat=0";
        return $this->db->executeQuery($query);
    }

    public function UpdateCawanganLamaStatus($id, $status = 1) { //status
        $query = "UPDATE cawangan_lama SET status = $status WHERE id = $id";
        return $this->db->executeQuery($query);
    }

    public function ReadCawanganAhli() {
        $query = "SELECT a.*, c.nama_cawangan "
            . "FROM ahli a "
            . "LEFT JOIN cawangan_lama c ON c.id=a.caw_id "
            . "WHERE a.stf_nama != '' AND stf_stat = 0 "
            . "ORDER BY nama_cawangan ASC";

        return $this->db->executeQuery($query);
    }

    public function InsertAhliCawangan($input) {
        $query = "INSERT INTO ahli (stf_nama, stf_tel, bank_id, stf_akaun, caw_id, usr_id, stf_stat) "
        . "VALUE ( "
        . "'" . $this->db->escape($input['nama']) . "', "
        . "'" . $this->db->escape($input['no_telefon']) . "', "
        . "0, "
        . "'" . $this->db->escape($input['akaun_bank']) . "', "
        . "'" . (int) $input['cawangan_id'] . "', "
        . "'" . (int) $input['profile_uid'] . "', "
            . "0 "
            . ")";
        return $this->db->executeQuery($query);
    }

    public function UpdateAhliCawangan($data) {
        $query = "UPDATE ahli SET "
        . "stf_nama = '" . $this->db->escape($data['nama']) . "', "
        . "stf_tel = '" . $this->db->escape($data['no_telefon']) . "', "
        . "stf_akaun = '" . $this->db->escape($data['akaun_bank']) . "' "
        . "WHERE stf_id = '" . (int) $data['stf_id'] . "'";
        return $this->db->executeQuery($query);
    }

    public function DeleteAhliCawangan($staff_id){
        $query = "UPDATE ahli SET stf_stat = 1 "
            . "WHERE stf_id = ". (int) $staff_id." ";
        $this->db->executeQuery($query);
    }
}