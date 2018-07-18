<?php

class Alirantunai_Controller extends Common_Controller {
    
    private $alirantunai_table;
    
     public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'SemuaAliranTunai' : $method . ($api['class_method']);
        $this->alirantunai_table = new Aliran_Tunai_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function GetSemuaAliranTunai() {
        return $this->alirantunai_table->ReadSemuaAliranTunai();
    }

}