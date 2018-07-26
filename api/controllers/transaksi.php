<?php

class Transaksi_Controller extends Common_Controller {

    private $transaksi_jualan_table;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'AllStocks' : $method . ($api['class_method']);
        $this->transaksi_jualan_table = new Transaksi_Jualan_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function PostJualan() {
        $jualan = $this->data;
        $nobil = $this->transaksi_jualan_table->GetCurrentNoBilAndUpdate();
        foreach ($jualan as $j):
            $data = array(
                'cawangan' => $j['cawangan'],
                'tarikh' => $this->DbDate($j['tarikh']),
                'perkara' => $j['perkara'],
                'market' => $j['market'],
                'tolak' => $j['tolak'],
                'berat' => $j['berat'],
                'gst' => $j['gst'],
                'hargaGst' => $j['hargaGst'],
                'harga' => $j['hargaClean'],
                'nobil' => $nobil
            );
            $this->transaksi_jualan_table->CreateTransaksiJualan($data);
        endforeach;
        return array('no_resit' => $nobil);
    }

    protected function GetJualan() {
        return $this->transaksi_jualan_table->ReadAllJualan();
    }

    protected function GetJualanResit($params) {
        $jualan = $this->transaksi_jualan_table->ReadJualanResit($params['id']);
        $maxItem = 21 - count($jualan);
        if ($maxItem > 0):
            $newMax = count($jualan) + $maxItem;
            for ($i = count($jualan); $i < $newMax; $i++):
                $jualan[$i] = array('isempty' => true);
            endfor;
        endif;
        return $jualan;
    }

    protected function DeleteInvoice() {
        $info = $this->data;
        $this->transaksi_jualan_table->DeleteRekodJualan($info['resit_no']);
        return true;
    }

    protected function PostAliranTunai() {
        $aliran_tunai = new Aliran_Tunai_Model();
        $dataArray = $this->data;
        $newData = array();
        foreach ($dataArray as $data):
            if ($data['kategori'] == 1):
                $data['emas_berat'] = (0 - $data['emas_berat']);
                $data['nilai'] = (0 - $data['nilai']);
            endif;
            $data['zak'] = ($data['kategori'] == 1) ? $data['akaun_zak'] : 0;
            $data['ref_bank'] = (isset($data['ref_bank'])) ? $data['ref_bank'] : 0;
            $data['user'] = '';
            $aliran_tunai->CreateAliranTunai($data);
            $newData[] = $data;
        endforeach;

        $result = array(
            'data' => $newData
        );
        return $result;
    }

    protected function PostAliranBank() {
        $aliran_tunai = new Aliran_Tunai_Model();
        $aliran_bank = new Aliran_Bank_Model();

        foreach ($this->data as $data):
            $data['nilai'] = 0;
            $data['emas_berat'] = 0;
            $data['cawangan_id'] = 46;
            $data['zak'] = 0;
            $data['stf_id'] = '68';
            $data['ref_bank'] = '';
            $data['ref_at_id'] = 0;
            $no_ab = $aliran_bank->CreateAliranBank($data);
            if ($data['kategori'] == 2):
                $data['kategori'] = 1;
                $data['jumlah'] = $data['jenis_keluar'];
                $data['ref_bank'] = $no_ab;
                $data['user'] = 1;
                $aliran_tunai->CreateAliranTunai($data);
            endif;
        endforeach;
    }
    
    protected function GetAliranTunaiTerkini($params){
        $aliran_tunai = new Aliran_Tunai_Model();
        $tarikhMula = (isset($params['tarikh_mula'])) ? $params['tarikh_mula'] : false;
        $tarikhAkhir = (isset($params['tarikh_akhir'])) ? $params['tarikh_akhir'] : false;
        $result = array(
            'list' => $aliran_tunai->ReadAliranTunaiTerkini($tarikhMula, $tarikhAkhir)
        );
        return $result;
    }
    
    protected function DeleteAliranwang(){
        $aliran_tunai = new Aliran_Tunai_Model();
        $aliran_tunai_deleted = new Aliran_Tunai_Deleted_Model();
        foreach($this->data as $data):
            $aliran_tunai_deleted->CreateAliranTunaiDeleted($data);
            $aliran_tunai->DeleteAliranWang($data);
        endforeach;
        $result = 'Items deleted!';
        return $result;
    }

}
