<?php

class Aliranbank_Controller extends Common_Controller {

    private $aliranbank_table;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'SemuaAliranBank' : $method . ($api['class_method']);
        $this->aliranbank_table = new Aliran_Bank_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function GetSemuaAliranBank() {
        return $this->cawangan_table->ReadSemuaCawangan();
    }

//    protected function PostAliranBank() {
//        $cawangan = $this->data;
//        $this->cawangan_table->CreateCawangan($cawangan);
//    }
    
}
