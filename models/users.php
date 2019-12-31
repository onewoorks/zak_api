<?php

class Users_Model extends Common_Model {

    public function ReadUser($username, $password) {
        $query = "SELECT * FROM tbl_user WHERE usr_name='" . $this->db->escape($username) . "' AND usr_password='$password'";
        return $this->db->executeQuery($query, 'single');
    }

    public function AddUserSession($user, $request_info, $token) {
        $query = "INSERT INTO users_session "
        . "(user_id, request_info, session_token) "
        . "VALUE "
        . "('" . (int) $user . "','" . $request_info . "','" . $token . "')";
        $this->db->executeQuery($query);
    }

    public function ReadSenaraiKakitangan() {
        $query = "SELECT * FROM tbl_user WHERE enabled = 1";
        return $this->db->executeQuery($query);
    }

    public function CreateKakitangan($input) {
        $query = "INSERT INTO tbl_user (usr_fname, usr_name, usr_password, usr_login) VALUE ("
        . "'" . $this->db->escape($input['nama_penuh']) . "', "
        . "'" . $this->db->escape($input['nama_pengguna']) . "', "
        . "'" . $this->db->escape($input['kata_laluan']) . "', "
            . "'0');";
        $this->db->executeQuery($query);
    }

    public function DeleteKakitangan($staff_id){
        $query = "UPDATE tbl_user SET enabled=0 WHERE usr_id = $staff_id";
        $this->db->executeQuery($query);
    }

    public function UpdateKakitangan($input){
        $query = "UPDATE tbl_user SET "
        . "usr_fname = '".$this->db->escape($input['nama_penuh'])."', "
        . "usr_password = '". $this->db->escape($input['kata_laluan'])."' "
        . "WHERE usr_id = '". (int) $input['usr_id']."' ";
        $this->db->executeQuery($query);
    }

}