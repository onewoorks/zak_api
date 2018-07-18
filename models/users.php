<?php

class Users_Model extends Common_Model {

    public function ReadUser($username, $password) {
        $query = "SELECT * FROM users WHERE username='" . $this->db->escape($username) . "' AND password='$password'";
        return $this->db->executeQuery($query, 'single');
    }

    public function AddUserSession($user,$request_info,$token) {
        $query = "INSERT INTO users_session "
                . "(user_id, request_info, session_token) "
                . "VALUE "
                . "('" . (int) $user . "','" . $request_info . "','" . $token . "')";
        $this->db->executeQuery($query);
    }

}
