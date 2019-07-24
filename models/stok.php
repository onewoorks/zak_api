<?php

class Stok_Model extends Common_Model
{

    public function ReadSemuaBank($limit = 100)
    {
        $query = "SELECT * FROM bank_account "
            . "LIMIT $limit";
        return $this->db->executeQuery($query);
    }

    public function ReadStokDariAliranTunai($cawangan_id = false)
    {
        $query = "select
        a.caw_id,
        c.nama_cawangan as cawangan,
        if(a.at_kategori = 1, a.at_guna, 0 ) as nilai_jual,
        if(a.at_kategori = 1, a.at_beratEmas, 0) as berat_jual,
        if(a.at_kategori = 2, a.at_guna, 0 ) as nilai_beli,
        if(a.at_kategori = 2, a.at_beratEmas, 0) as berat_beli
        from aliran_tunai a
        left join cawangan_lama c on c.id = a.caw_id
        where
        a.at_beratEmas > 0 ";
        if ($cawangan_id):
            $query .= "AND a.caw_id ='" . (int) $cawangan_id . "' ";
        endif;
        return $this->db->executeQuery($query);
    }

}
