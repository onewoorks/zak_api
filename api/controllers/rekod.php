<?php

class Rekod_Controller extends Common_Controller {

    private $alirantunai_table;
    private $aliranbank_table;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'SemuaAliranTunai' : $method . ($api['class_method']);
        $this->alirantunai_table = new Aliran_Tunai_Model();
        $this->aliranbank_table = new Aliran_Bank_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function GetSemuaAliranTunai($params) {
        $tarikhMula = (isset($params['tarikh_mula'])) ? $params['tarikh_mula'] : false;
        $tarikhAkhir = (isset($params['tarikh_akhir'])) ? $params['tarikh_akhir'] : false;
        $result = array(
            'list' => $this->alirantunai_table->ReadSemuaAliranTunai($tarikhMula, $tarikhAkhir),
            'summary' => $this->alirantunai_table->ReadSummaryAliranTunai($tarikhMula, $tarikhAkhir)
        );
        return $result;
    }

    protected function GetSemuaAliranBank($params) {
        $tarikhMula = (isset($params['tarikh_mula'])) ? $params['tarikh_mula'] : false;
        $tarikhAkhir = (isset($params['tarikh_akhir'])) ? $params['tarikh_akhir'] : false;
        $result = array(
            'list' => $this->aliranbank_table->ReadSemuaAliranBank($tarikhMula, $tarikhAkhir),
            'summary' => $this->aliranbank_table->ReadSummaryAliranBank($tarikhMula, $tarikhAkhir)
        );
        return $result;
    }

    protected function GetTransaksiHariIni() {
        $result = array(
            'list' => $this->alirantunai_table->ReadTransaksi(),
            'summary' => $this->alirantunai_table->ReadSummaryTransaksi()
        );
        return $result;
    }

    protected function GetTransaksiBulanIni($params) {
        $result = array(
            'last_month' => $this->alirantunai_table->ReadBakiBulanLepas(),
            'list' => $this->alirantunai_table->ReadTransaksi(false),
            'summary' => $this->alirantunai_table->ReadSummaryTransaksi(false),
            'summary_combine_masuk' => $this->alirantunai_table->ReadBakiBulanLepas()['baki'] + $this->alirantunai_table->ReadSummaryTransaksi(false)['masuk']
        );
        return $result;
    }

    protected function GetPilihan($params) {
        $result = array(
            'list' => $this->alirantunai_table->ReadRekodPilihan($params['cawangan'], $params['tarikh_mula'], $params['tarikh_akhir']),
            'summary' => array(
                "semua" => $this->alirantunai_table->ReadRekodPilihanSummary($params['cawangan']),
                "pilihan" => $this->alirantunai_table->ReadRekodPilihanSummary($params['cawangan'], $params['tarikh_mula'], $params['tarikh_akhir'])
            )
        );
        return $result;
    }

}
