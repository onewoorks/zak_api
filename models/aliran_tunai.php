<?php

class Aliran_Tunai_Model extends Common_Model {
    
    public function ReadSemuaAliranTunai($limit = 100) {
        $query = "SELECT at.*, a.stf_nama FROM aliran_tunai at "
                . "LEFT JOIN ahli a ON a.stf_id=at.stf_id "
                . "ORDER BY at_id DESC "
                . "LIMIT $limit";
        return $this->db->executeQuery($query);
    }
    
    public function ReadSummaryAliranTunai(){
        $query = "SELECT "
                . "sum(if(at_kategori=1, at_jumlah, 0)) as jumlah_masuk, "
                . "sum(if(at_kategori=2, at_jumlah, 0)) as jumlah_keluar, "
                . "sum(at_beratEmas) as berat_emas,sum(at_guna) as nilai_emas "
                . "FROM aliran_tunai";
        return $this->db->executeQuery($query,'single');
    }
    
    public function ReadTransaksi($today = true){
        if($today):
            $where_query = "DATE(at.at_timeDate) = '".date('Y-m-d')."' ";
        else:
            $where_query = "YEAR(at_timeDate) >= '".date('Y')."' AND MONTH(at_timeDate) = '".date('m')."'";
        endif;
        $query = "SELECT at.*, "
                . "DATE_FORMAT(at.at_timeDate, '%d/%m/%Y %H:%i') as at_timeDate, "
                . "a.stf_nama AS ahli,"
                . "c.caw_nama AS cawangan, "
                . "IF(at_kategori=2,at_jumlah,0) - IF(at_kategori=1,at_jumlah,0) as hutang "
                . "FROM aliran_tunai at "
                . "LEFT JOIN ahli a ON a.stf_id=at.stf_id "
                . "LEFT JOIN tbl_cawangan c ON c.caw_id=at.caw_id "
                . "WHERE $where_query "
                . "ORDER BY at.at_id ";
        return $this->db->executeQuery($query);
    }
    
    public function ReadSummaryTransaksi($today = true){
        if($today):
            $where_query = "DATE(at.at_timeDate) = '".date('Y-m-d')."' ";
        else:
            $where_query = "YEAR(at_timeDate) >= '".date('Y')."' AND MONTH(at_timeDate) = '".date('m')."'";
        endif;
        $query = "SELECT "
                . "SUM(IF(at_kategori=1,at_jumlah,0)) as masuk, "
                . "SUM(IF(at_kategori=2,at_jumlah,0)) as keluar "
                . "FROM aliran_tunai "
                . "WHERE $where_query";
        return $this->db->executeQuery($query,'single');
    }
    
    public function ReadBakiBulanLepas(){
        $query = "SELECT "
                . "SUM(if(at_kategori=1, at_jumlah, 0)) - SUM(if(at_kategori=2, at_jumlah, 0)) as baki, "
                . "SUM(at_beratEmas) as berat_emas, "
                . "SUM(at_guna) as emas_guna "
                . "FROM aliran_tunai "
                . "WHERE year(at_timeDate) = '2018' AND month(at_timeDate) = '06'";
        return $this->db->executeQuery($query, 'single');
    }

}