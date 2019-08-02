<?php

class Jual_Emas_Model extends Common_Model
{

  public function ReadSemuaJualan($status = 'SUDAH JUAL'){
      $query = "SELECT "
            . "c.nama_cawangan "
            . ",e.* "
            . "FROM emas_jual e "
            . "LEFT JOIN cawangan_lama c ON c.id = e.cawangan_id "
            . "WHERE e.status_jual = '". $this->db->escape($status). "' "
            . "GROUP BY e.cawangan_id ";
            return $this->db->executeQuery($query);
  }

}
