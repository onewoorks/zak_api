<?php

class Stok_Controller extends Common_Controller {
    
    private $stok_model;
    private $stok_ambil_model;
    
    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? 'default_' : $method . ($api['class_method']);
        $this->stok_model = new Stok_Model();
        $this->stok_ambil_model = new Ambil_Emas_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }
    
    protected function default_(){
        
    }
    
    protected function GetBankList(){
        return $this->bank_table->ReadSemuaBank();
    }

    private function currentCawanganStock($stok,$info_stok_baru){
        $stok['nilai_jual'] += $info_stok_baru['nilai_jual'];
        $stok['berat_jual'] += $info_stok_baru['berat_jual'];
        $stok['nilai_beli'] += $info_stok_baru['nilai_beli'];
        $stok['berat_beli'] += $info_stok_baru['berat_beli'];
        return $stok;
    }

    protected function GetStokInfo($params){
        $cawangan_id = (isset($params['caw_id'])) ? $params['caw_id'] : False;
        $list_at = $this->stok_model->ReadStokDariAliranTunai($cawangan_id);
        $clean = array();
        $itemize = array();
        $summarize = array();
        if(isset($params['caw_id'])):
            $itemize = $list_at;
            $this->array_sort_by_column($itemize, 'tarikh_transaksi', SORT_DESC);
        else:
            foreach($list_at as $at):
                if(isset($itemize[$at['caw_id']])):
                    $itemize[$at['caw_id']] = $this->currentCawanganStock($itemize[$at['caw_id']],$at);
                else:
                    $itemize[$at['caw_id']] = $at;
                endif;
            endforeach;
            $this->array_sort_by_column($itemize, 'nilai_beli', SORT_DESC);
        endif;
        $summarize = $this->stok_model->ReadSumStokDariAliranTunai($cawangan_id);
        return array(
            'itemize' => $itemize,
            'summary' => $summarize
        );
    }

    protected function GetStokEmasAmbil(){
        $stok_ambil = $this->stok_ambil_model->RekodAmbilEmas('AMBIL');
        $berat_emas = array_sum(array_column($stok_ambil,'berat_ambil'));
        return array(
            'itemize' => $stok_ambil,
            'summary' => array(
                'berat_emas' => $berat_emas
            )
        );
    }

}
