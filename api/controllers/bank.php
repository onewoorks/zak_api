<?php

class Bank_Controller extends Common_Controller {
    
    private $bank_table;
    
    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? 'default_' : $method . ($api['class_method']);
        $this->bank_table = new Bank_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }
    
    protected function default_(){
        
    }
    
    protected function GetBankList(){
        return $this->bank_table->ReadSemuaBank();
    }
    
}
