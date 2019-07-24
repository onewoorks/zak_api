<?php

class Stok_Controller extends Common_Controller {
    
    private $stok_model;
    
    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? 'default_' : $method . ($api['class_method']);
        $this->stok_model = new Stok_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }
    
    protected function default_(){
        
    }
    
    protected function GetBankList(){
        return $this->bank_table->ReadSemuaBank();
    }

    protected function GetStokInfo($params){
        $cawangan_id = (isset($params['caw_id'])) ? $params['caw_id'] : False;
        $list_at = $this->stok_model->ReadStokDariAliranTunai($cawangan_id);
        $clean = array();
        foreach($list_at as $at):
            $clean[$at['caw_id']] = $at;
        endforeach;
        return $clean;
    }

    
}
