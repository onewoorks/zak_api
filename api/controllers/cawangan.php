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
    
    protected function GetCawanganZak1(){
        return $this->cawangan_table->ReadSemuaCawanganLama();
    }
    
    protected function GetCawanganHead(){
        return $this->cawangan_table->ReadSemuaCawanganHead();
    }
    
    protected function GetKakitangan(){
        return $this->cawangan_table->ReadSemuaKakitangan();
    }
    
    protected function GetCawanganDetail($params){
        return $this->cawangan_table->ReadCawanganDetail($params['id']);
    }

    protected function GetCawanganDetailLama($params){
        return $this->cawangan_table->ReadCawanganLamaDetail($params['id']);
    }
    
    protected function PostTambahCawangan(){
        $cawangan = $this->data;
        $this->cawangan_table->CreateCawangan($cawangan);
    }

    protected function PostTambahCawanganLama(){
        $cawangan = $this->data;
        $this->cawangan_table->CreateCawanganLama($cawangan);
    }
    
    protected function PutKemaskiniCawangan(){
        $cawangan = $this->data;
        $this->cawangan_table->UpdateCawangan($cawangan);
    }

    protected function PutKemaskiniCawanganLama(){
        $cawangan = $this->data;
        $this->cawangan_table->UpdateCawanganLama($cawangan);
    }

    protected function DeleteCawanganLama($params){
        $this->cawangan_table->UpdateCawanganLamaStatus($params['id'],1);
    }

    protected function GetCawanganAhli(){
        return $this->cawangan_table->ReadCawanganAhli();
    }

    protected function PostTambahCawanganAhli(){
        $ahli = $this->data;
        $this->cawangan_table->InsertAhliCawangan($ahli);
    }

    protected function PutCawanganAhli(){
        $ahli = $this->data;
        $this->cawangan_table->UpdateAhliCawangan($ahli);
    }

    protected function DeleteCawanganAhli($params){
        $this->cawangan_table->DeleteAhliCawangan($params['id']);
    }
}
