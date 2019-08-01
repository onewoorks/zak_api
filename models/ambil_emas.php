<?php

class Ambil_Emas_Model extends Common_Model {

    public function CreateAmbilEmas($data) {
        $query = "INSERT INTO emas_ambil "
                . "(perkara, tarikh_ambil, berat_ambil, status_rekod, cawangan_id, cawangan_user, no_resit_jualan) VALUE ("
                . "'" . $this->db->escape(strtoupper($data['perkara'])) . "', "
                . "'" . $this->db->escape($data['tarikh']) . "', "
                . "'" . $this->db->escape($data['berat_ambil']) . "', "
                . "'" . $this->db->escape($data['status_rekod'])."', "
                . "'" . $this->db->escape($data['cawangan_id']) . "', "
                . "'" . $this->db->escape($data['cawangan_user']) . "', "
                . "" . (int) $data['user'] . " "
                . ")";
        return $this->db->executeQuery($query);
    }
    
    public function RekodAmbilEmas(){
        $query = "SELECT "
            . "e.tarikh_ambil as tarikh_ambil "
            . ", SUM(e.berat_ambil) as berat_ambil "
            . ", c.nama_cawangan as nama_cawangan "
            . ", a.stf_nama as nama_staff "
            . ", e.cawangan_id "
            . "FROM "
            . "emas_ambil e "
            . "LEFT JOIN cawangan_lama c on c.id = e.cawangan_id "
            . "LEFT JOIN ahli a on a.stf_id = e.cawangan_user "
            . "GROUP BY cawangan_id ";
        return $this->db->executeQuery($query);
    }

    public function CreateHantarEmas($data){
        $query = "INSERT INTO emas_tarik "
                . "(cawangan_id, status_tarik, tarikh_hantar, berat_hantar, tarikh_siap, berat_selepas_tarik, nilai_selepas_tarik, staff_id) VALUE ("
                . "'" . $this->db->escape($data['cawangan_id']) . "', "
                . "'" . $this->db->escape($data['status_tarik']) . "', "
                . "'" . $this->db->escape($data['tarikh_hantar']) . "', "
                . "'" . $this->db->escape($data['berat_hantar']) . "', "
                . "'" . $this->db->escape($data['tarikh_siap']) . "', "
                . "'" . $this->db->escape($data['berat_selepas_tarik'])."', "
                . "'" . $this->db->escape($data['nilai_selepas_tarik']) . "', "
                . "'" . $this->db->escape($data['user']) . "' "
                . ")";
        return $this->db->executeQuery($query);
    }

    public function PutEmasLepasTarik($data){
        $query = "UPDATE emas_tarik SET "
            . "berat_selepas_tarik = '".(float) $data['berat_selepas_tarik']."', " 
            . "nilai_selepas_tarik = '".(float) $data['nilai_selepas_tarik']."', " 
            . "status_tarik = '".$this->db->escape($data['status_tarik'])."', "
            . "tarikh_siap = '". $this->db->escape($data['tarikh_siap'])."' "
            . "WHERE cawangan_id= '".(int) $data['cawangan_id']."'";
            echo $query;
        return $this->db->executeQuery($query);
    }

    public function ReadEmasDalamProcessTarik(){
        $query = "SELECT "
                . "e.* "
                . ", SUM(e.berat_hantar) "
                . ", c.nama_cawangan "
                . "FROM emas_tarik e "
                . "LEFT JOIN cawangan_lama c ON e.cawangan_id = c.id "
                . "WHERE status_tarik = 'HANTAR' "
                . "GROUP BY e.cawangan_id ";
        return $this->db->executeQuery($query);
    }

    public function ReadAmbilEmasUntukJual(){
        $query = "SELECT "
                . "e.* "
                . ", SUM(e.berat_hantar) AS berat_stok "
                . ", c.nama_cawangan "
                . "FROM emas_tarik e "
                . "LEFT JOIN cawangan_lama c ON e.cawangan_id = c.id "
                . "WHERE status_tarik = 'SIAP TARIK' "
                . "GROUP BY e.cawangan_id ";
        return $this->db->executeQuery($query);
    }

    public function CreateSediaJual($data){
        $query = "INSERT INTO emas_jual "
                . "(cawangan_id, status_jual, tarikh_jual, berat_jual, harga_modal, harga_jual, untung, no_resit_jualan) VALUE ("
                . "'" . $this->db->escape($data['cawangan_id']) . "', "
                . "'" . $this->db->escape($data['status_jual']) . "', "
                . "'" . $this->db->escape($data['tarikh_jual']) . "', "
                . "'" . $this->db->escape($data['berat_jual']) . "', "
                . "'" . $this->db->escape($data['harga_modal']) . "', "
                . "'" . $this->db->escape($data['harga_jual'])."', "
                . "'" . $this->db->escape($data['untung']) . "', "
                . "'" . $this->db->escape($data['no_resit_jualan']) . "' "
                . ")";
        return $this->db->executeQuery($query);
    }

    public function ReadEmasUntukJual($status){
        $query = "SELECT "
                . "c.nama_cawangan, "
                . "SUM(e.berat_jual) AS berat_jual "
                . "FROM emas_jual e "
                . "LEFT JOIN cawangan_lama c ON c.id = e.cawangan_id "
                . "WHERE status_jual = '". $this->db->escape($status)."' "
                . "GROUP BY e.cawangan_id ";
        return $this->db->executeQuery($query);
    }

}
