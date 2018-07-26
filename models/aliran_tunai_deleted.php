<?php

class Aliran_Tunai_Deleted_Model extends Common_Model {

    public function CreateAliranTunaiDeleted($data) {
        $query = "INSERT INTO aliran_tunai_deleted "
                . "(at_perkara, at_kategori, at_jumlah, at_guna, at_beratEmas, stf_id, caw_id, usr_id, at_zak, ref_ab_id) VALUE ("
                . "'" . $this->db->escape(strtoupper($data['at_perkara'])) . "', "
                . "'" . $this->db->escape($data['at_kategori']) . "', "
                . "'" . $this->db->escape($data['at_jumlah']) . "', "
                . "'" . $this->db->escape($data['at_guna']) . "', "
                . "'" . $this->db->escape($data['at_beratEmas']) . "', "
                . "'" . $this->db->escape($data['stf_id']) . "', "
                . "'" . $this->db->escape($data['caw_id']) . "', "
                . "" . (int) $data['usr_id'] . ", "
                . "'" . $this->db->escape($data['at_zak']) . "', "
                . "'" . (int) $data['ref_ab_id'] . "'"
                . ")";
        return $this->db->executeQuery($query);
    }

}
