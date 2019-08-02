<?php

class Laporan_Controller extends Common_Controller {

    private $jual_emas;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'SemuaAliranBank' : $method . ($api['class_method']);
        $this->jual_emas = new Jual_Emas_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }


    protected function GetSemuaJualan($params = false){
        $itemize = $this->jual_emas->ReadSemuaJualan($params);
        $jumlah_modal = 0;
        $jumlah_berat = 0;
        $jumlah_harga_jual = 0;
        $jumlah_untung = 0;
        foreach($itemize as $item):
            $jumlah_modal += $item['harga_modal'];
            $jumlah_berat += $item['berat_jual'];
            $jumlah_harga_jual += $item['harga_jual'];
            $jumlah_untung += $item['harga_jual'] - $item['harga_modal'];
        endforeach;
        $summary = array(
            "jumlah_modal" => $jumlah_modal,
            "jumlah_berat" => $jumlah_berat,
            "jumlah_harga_jual" => $jumlah_harga_jual,
            "jumlah_untung" => $jumlah_untung
        );
        return array(
            "itemize" => $itemize,
            "summary" => $summary
        );
    }    
}
