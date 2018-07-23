<?php

class Dashboard_Controller extends Common_Controller {

    private $cawangan_table;
    private $alirantunai_table;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? 'default_' : $method . ($api['class_method']);
        $this->cawangan_table = new Cawangan_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function default_() {
        return false;
    }

    protected function GetSummary() {
        $aliranTunai = new Aliran_Tunai_Model();
        $aliranBank = new Aliran_Bank_Model();
        $result = array(
            'aliran_tunai' => $aliranTunai->ReadAliranTunai('bulanan'),
            'aliran_emas' => $aliranTunai->ReadAliranEmasBulanIni(),
            'aliran_tunai_semua' => $aliranTunai->ReadAliranTunai(),
            'aliran_bank_semua' => $aliranBank->ReadAliranBank(),
            'hutang_piutang'=> $aliranTunai->ReadHutangPiutang(),
            'graf_aliran_wang' => $aliranTunai->ReadAliranWangMasukKeluar($bulan)
        );
        return $result;
    }

}
