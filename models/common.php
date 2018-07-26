<?php

class Common_Model {
    
    protected $date_format = '%d/%m/%Y %H:%i:%s';
    
    public function __construct() {
        $this->db = new Mysql_Driver();
    }
    
    public  function GetCurrentNoBilAndUpdate(){
        $query = "SELECT data FROM reference WHERE name='bil_jualan'";
        $nobil = $this->db->executeQuery($query,'single')['data'];
        $update = "UPDATE reference SET data='".(int) ($nobil+1)."' WHERE name='bil_jualan'";
        $this->db->executeQuery($update);
        return $nobil;
    }
}

