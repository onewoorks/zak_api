<?php

class Jual_Emas_Model extends Common_Model
{

  public function ReadSemuaJualan($params = false, $status = 'SUDAH JUAL'){
    $where = "";
    if($params):
      $where = "AND DATE(e.tarikh_jual) >= '" . $params['tarikh_mula'] . "' ";
      $where .= "AND DATE(e.tarikh_jual) <= '" . $params['tarikh_akhir'] ."' ";
    endif;
    $query = "SELECT "
          . "c.nama_cawangan, "
          . "sum(e.berat_jual) as berat_jual, "
          . "sum(e.harga_modal) as harga_modal, "
          . "sum(e.harga_jual) as harga_jual, "
          . "sum(e.untung) as untung "
          . "FROM emas_jual e "
          . "LEFT JOIN cawangan_lama c ON c.id = e.cawangan_id "
          . "WHERE e.status_jual = '". $this->db->escape($status). "' "
          . $where
          . "GROUP BY e.cawangan_id ";
    return $this->db->executeQuery($query);
  }

}
