<?php

class Aliran_Tunai_Model extends Common_Model {

    public function ReadSemuaAliranTunai($limit = 100) {
        $query = "SELECT at.*, a.stf_nama FROM aliran_tunai at "
                . "LEFT JOIN ahli a ON a.stf_id=at.stf_id "
                . "ORDER BY at_id DESC "
                . "LIMIT $limit";
        return $this->db->executeQuery($query);
    }

    public function ReadSummaryAliranTunai() {
        $query = "SELECT "
                . "sum(if(at_kategori=1, at_jumlah, 0)) as jumlah_masuk, "
                . "sum(if(at_kategori=2, at_jumlah, 0)) as jumlah_keluar, "
                . "sum(at_beratEmas) as berat_emas,sum(at_guna) as nilai_emas "
                . "FROM aliran_tunai";
        return $this->db->executeQuery($query, 'single');
    }

    public function ReadTransaksi($today = true) {
        if ($today):
            $where_query = "DATE(at.at_timeDate) = '" . date('Y-m-d') . "' ";
        else:
            $where_query = "YEAR(at_timeDate) >= '" . date('Y') . "' AND MONTH(at_timeDate) = '" . date('m') . "'";
        endif;
        $query = "SELECT at.*, "
                . "DATE_FORMAT(at.at_timeDate, '%d/%m/%Y %H:%i') as at_timeDate, "
                . "a.stf_nama AS ahli,"
                . "c.nama_cawangan AS cawangan, "
                . "IF(at_kategori=2,at_jumlah,0) - IF(at_kategori=1,at_jumlah,0) as hutang "
                . "FROM aliran_tunai at "
                . "LEFT JOIN ahli a ON a.stf_id=at.stf_id "
                . "LEFT JOIN cawangan_lama c ON c.id=at.caw_id "
                . "WHERE $where_query "
                . "ORDER BY at.at_id ";
        return $this->db->executeQuery($query);
    }

    public function ReadSummaryTransaksi($today = true) {
        if ($today):
            $where_query = "DATE(at.at_timeDate) = '" . date('Y-m-d') . "' ";
        else:
            $where_query = "YEAR(at_timeDate) >= '" . date('Y') . "' AND MONTH(at_timeDate) = '" . date('m') . "'";
        endif;
        $query = "SELECT "
                . "SUM(IF(at_kategori=1,at_jumlah,0)) as masuk, "
                . "SUM(IF(at_kategori=2,at_jumlah,0)) as keluar "
                . "FROM aliran_tunai "
                . "WHERE $where_query";
        return $this->db->executeQuery($query, 'single');
    }

    public function ReadBakiBulanLepas() {
        $query = "SELECT "
                . "SUM(if(at_kategori=1, at_jumlah, 0)) - SUM(if(at_kategori=2, at_jumlah, 0)) as baki, "
                . "SUM(at_beratEmas) as berat_emas, "
                . "SUM(at_guna) as emas_guna "
                . "FROM aliran_tunai "
                . "WHERE year(at_timeDate) = '2018' AND month(at_timeDate) = '06'";
        return $this->db->executeQuery($query, 'single');
    }

    public function ReadRekodPilihan($id_cawangan, $tarikhMula, $tarikhAkhir) {
        $query = "SELECT * "
                . "FROM aliran_tunai "
                . "WHERE caw_id= '" . (int) $id_cawangan . "' "
                . "AND date(at_timeDate) >= '$tarikhMula' AND date(at_timeDate) <= '$tarikhAkhir'";
        return $this->db->executeQuery($query);
    }

    public function ReadAliranTunai($jenis = 'semua') {
        $q_where = '1';
        switch ($jenis):
            case 'bulanan':
                $q_where = 'year(at_timeDate)=year(now()) and month(`at_timeDate`)=month(now())';
                break;
            default:
                break;
        endswitch;
        $query = "SELECT "
                . "SUM(IF(at_kategori=1, at_jumlah, 0)) as masuk, "
                . "SUM(IF(at_kategori=2, at_jumlah, 0)) as keluar, "
                . "SUM(IF(at_kategori=1, at_jumlah, 0))-SUM(IF(at_kategori=2, at_jumlah, 0)) as baki "
                . "FROM aliran_tunai "
                . "WHERE $q_where";
        return $this->db->executeQuery($query, 'single');
    }

    public function ReadAliranEmasBulanIni() {
        $query = "SELECT "
                . "CASE "
                . " WHEN at_kategori = 1 THEN 'jualan' "
                . " WHEN at_kategori = 2 THEN 'belian' "
                . "END AS kategori, "
                . "SUM(at_beratEmas) as berat_emas, "
                . "SUM(at_guna) as nilai_emas "
                . "FROM aliran_tunai "
                . "WHERE "
                . "YEAR(at_timeDate)=YEAR(now()) AND MONTH(`at_timeDate`)=MONTH(now()) "
                . "GROUP BY at_kategori";
        return $this->db->executeQuery($query);
    }

    public function ReadHutangPiutang() {
        $query = "SELECT "
                . "c.id, c.cawangan_head as cawangan, "
                . "SUM(if(at.at_kategori=1, (at.at_guna + at.at_jumlah), 0 )) as masuk, "
                . "SUM(if(at.at_kategori=2, (at.at_guna + at.at_jumlah), 0 )) as keluar, "
                . "(SUM(if(at.at_kategori=1, (at.at_guna + at.at_jumlah), 0 )) - SUM(if(at.at_kategori=2, (at.at_guna + at.at_jumlah), 0 ))) as baki "
                . "FROM aliran_tunai at "
                . "LEFT JOIN cawangan_lama c on c.id=at.caw_id where c.id IS NOT NULL "
                . "GROUP BY at.caw_id having baki != 0 "
                . "ORDER BY baki desc";
        return $this->db->executeQuery($query);
    }
    
    public function ReadAliranWangMasukKeluar($bulan = 6){
        $query = "SELECT "
                . "CONCAT(MONTH(at_timeDate),' ',YEAR(at_timeDate)) as bulan, "
                . "SUM(IF(at_kategori=1, at_jumlah,0)) as masuk, "
                . "SUM(IF(at_kategori=2, at_jumlah,0)) as keluar "
                . "FROM aliran_tunai "
                . "WHERE DATE(`at_timeDate`) >= '2018-02-01' AND DATE(at_timeDate) <= now() "
                . "GROUP BY YEAR(at_timeDate), MONTH(at_timeDate)";
        return $this->db->executeQuery($query);
    }
    

}
