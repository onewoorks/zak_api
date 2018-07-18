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

    protected function GetSemuaAliranTunai() {
        $result = array(
            'list'=> $this->alirantunai_table->ReadSemuaAliranTunai(),
            'summary'=> $this->alirantunai_table->ReadSummaryAliranTunai()
        );
        return $result;
    }
    
    protected function GetSemuaAliranBank(){
        $result = array(
            'list'=> $this->aliranbank_table->ReadSemuaAliranBank(),
            'summary'=> $this->aliranbank_table->ReadSummaryAliranBank()
        );
        return $result;
    }
    
    protected function GetTransaksiHariIni(){
        $result = array(
            'list'=> $this->alirantunai_table->ReadTransaksi(),
            'summary'=>$this->alirantunai_table->ReadSummaryTransaksi()
        );
        return $result;
    }
    
    protected function GetTransaksiBulanIni(){
        $result = array(
            'last_month'=> $this->alirantunai_table->ReadBakiBulanLepas(),
            'list'=> $this->alirantunai_table->ReadTransaksi(false),
            'summary'=>$this->alirantunai_table->ReadSummaryTransaksi(false),
            'summary_combine_masuk' => $this->alirantunai_table->ReadBakiBulanLepas()['baki']+$this->alirantunai_table->ReadSummaryTransaksi(false)['masuk']
        );
        return $result;
    }
    
   
}