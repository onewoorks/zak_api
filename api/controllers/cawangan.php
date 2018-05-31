<?php

class Cawangan_Controller extends Common_Controller {

    private $cawangan_table;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'SemuaCawangan' : $method . ($api['class_method']);
        $this->cawangan_table = new Cawangan_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function GetSemuaCawangan(){
        return $this->cawangan_table->ReadSemuaCawangan();
    }
    
    protected function GetCawanganDetail($params){
        return $this->cawangan_table->ReadCawanganDetail($params['id']);
    }
    
    protected function PostTambahCawangan(){
        $cawangan = $this->data;
        $this->cawangan_table->CreateCawangan($cawangan);
    }
    
    protected function PutKemaskiniCawangan(){
        $cawangan = $this->data;
        $this->cawangan_table->UpdateCawangan($cawangan);
    }


}
