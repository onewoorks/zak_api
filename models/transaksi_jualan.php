<?php

class Transaksi_Jualan_Model extends Common_Model {

    public function CreateTransaksiJualan($data_input){
        $query = "";
        foreach($data_input as $data):
            $query .= "INSERT INTO transaksi_jualan "
                    . "(cawangan, perkara, tarikh_jual, market, tolak, berat_jual, harga_jual, no_resit, nilai_gst, gst_rate) "
                    . "VALUE "
                    . "('".(int) $data['cawangan']."', "
                    . "'".$this->db->escape($data['perkara'])."', "
                    . "'".$data['tarikh']."', "
                    . "'".$data['market']."', "
                    . "'".$data['tolak']."', "
                    . "'".$data['berat']."', "
                    . "'".$data['harga']."', "
                    . "'".$data['nobil']."' ,"
                    . "'".$data['hargaGst']."', "
                    . "'".$data['gst']."'); ";
        endforeach;
        $this->db->executeQuery($query);
        return $this->db->lastId();
    }
    
    public function ReadAllJualan(){
        $query = "SELECT "
                . "DATE_FORMAT(j.tarikh_jual,'%e %M %Y') AS tarikh_jual, "
                . "j.no_resit as resit, "
                . "c.nama_cawangan AS nama_cawangan, "
                . "sum(j.harga_jual) AS harga_jual, "
                . "sum(j.nilai_gst) AS harga_gst,"
                . "sum(j.berat_jual) AS berat_jual "
                . "FROM transaksi_jualan j "
                . "LEFT JOIN cawangan c ON c.id=j.cawangan "
                . "GROUP BY j.no_resit";
        return $this->db->executeQuery($query);
    }
    
    public function ReadJualanResit($resit_id){
        $query = "SELECT  c.nama_cawangan, "
                . "c.alamat, c.no_telefon, "
                . "c.no_gst, "
                . "j.*, "
                . "DATE_FORMAT(j.tarikh_jual,'%e %M %Y') AS tarikh_jual, "
                . "j.no_resit as resit, "
                . "(SELECT sum(berat_jual) FROM transaksi_jualan WHERE no_resit = j.no_resit ) AS total_berat, "
                . "(SELECT sum(harga_jual) FROM transaksi_jualan WHERE no_resit = j.no_resit ) AS total_harga, "
                . "(SELECT sum(nilai_gst) FROM transaksi_jualan WHERE no_resit = j.no_resit ) AS total_gst "
                . "FROM transaksi_jualan j "
                . "LEFT JOIN cawangan c ON c.id=j.cawangan  WHERE j.no_resit = '".$resit_id."'";
        return $this->db->executeQuery($query);
    }
    
    public function DeleteRekodJualan($resit_id){
        $query = "DELETE FROM transaksi_jualan WHERE no_resit = '".$resit_id."'";
        return $this->db->executeQuery($query);
    }
}
