<?php

class Ambilemas_Controller extends Common_Controller {
    
    private $table_ambil_emas;
    
     public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'SemuaAliranTunai' : $method . ($api['class_method']);
        $this->table_ambil_emas = new Ambil_Emas_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }

    protected function GetSemuaAliranTunai() {
        return $this->table_ambil_emas->ReadSemuaAliranTunai();
    }

    public function PostAmbilEmas(){
        $data = $this->data;
        $ambil_emas = [];
        foreach($data as $d):
            $info = array(
                "perkara" => $d["perkara"],
                "tarikh" => $this->DbDate($d['tarikh']), 
                "berat_ambil" => $d['emas_berat'], 
                "status_rekod" => 'AMBIL', 
                "cawangan_id" => $d['cawangan_id'], 
                "cawangan_user" => $d['stf_id'],
                "no_resit_ambil" => $d['resit_ambil'],
                "user" => 1
            );
            $ambil_emas[] = $info;
        endforeach;
        $this->table_ambil_emas->CreateAmbilEmas($ambil_emas);
    }

    protected function GetEmasProsesTarik(){
        $itemize = $this->table_ambil_emas->ReadEmasDalamProcessTarik();
        $summary = array(
            "berat_emas" => array_sum(array_column($itemize,'berat_hantar'))
        );
        return array(
            'itemize' => $itemize,
            'summary' => $summary
        );
    }

    protected function PostHantarEmasUntukTarik(){
        $data = $this->data;
        $cawangan_id = [];
        $baki_ambil = [];
        foreach($data as $d):
            $cawangan_id[] = $d['cawangan_id'];
            $info = array(
                "cawangan_id" => $d['cawangan_id'],
                "status_tarik" => "HANTAR", 
                "tarikh_hantar" => date('Y-m-d'), 
                "berat_hantar" => $d['ambilan'],
                "user" => 1,
                "tarikh_siap" => date('Y-m-d'),
                "berat_selepas_tarik" => 0.00,
                "nilai_selepas_tarik" => 0.00
            );
            $this->table_ambil_emas->CreateHantarEmas($info);
            if ($d['baki_berat'] > 0):
                $info = $d;
                $info['perkara'] = 'BAKI BERAT, '. $d['tarikh_ambil'] . ', ' . $d['berat_ambil'];
                $info['berat_ambil'] = $d['baki_berat'];
                $info['status_rekod'] = 'AMBIL';
                $info['tarikh'] = $d['tarikh_ambil'];
                $info['cawangan_user'] = $d['cawangan_user'];
                $info['user'] = 1;
                $baki_ambil[] = $info;
            endif;
            
        endforeach;
        $cawangan = implode(',',$cawangan_id);
        $this->table_ambil_emas->UpdatePindahEmasUntukTarik($cawangan);
        $this->BakiEmasHantarTarik($baki_ambil);
    }   
    
    private function BakiEmasHantarTarik($data){
        if(count($data)>0):
            $this->table_ambil_emas->CreateAmbilEmas($data);
        endif;
    }

    protected function PutEmasLepasTarik(){
        $data = $this->data;
        $emas_lepas_tarik = [];
        foreach($data as $d):
            $input = array(
                "cawangan_id" => $d['cawangan_id'],
                "status_tarik" => 'SIAP TARIK',
                "berat_selepas_tarik" => $d['berat_lepas_tarik'],
                "nilai_selepas_tarik" => $d['nilai_lepas_tarik'],
                "tarikh_siap" => date('Y-m-d')
            );
            $emas_lepas_tarik[] = $input;
        endforeach;
        $this->table_ambil_emas->PutEmasLepasTarik($emas_lepas_tarik);
    }

    protected function GetStokEmasUntukJual(){
        $stok_list = $this->table_ambil_emas->ReadAmbilEmasUntukJual();
        return array(
            'itemize' => $stok_list
        );
    }

    protected function PutEmasProsesJual(){
        $data = $this->data;
        foreach($data as $d):
            $input = array(
                "cawangan_id" => $d['cawangan_id'],
                "status_jual" => 'UNTUK JUAL',
                "berat_jual" => $d['berat_lepas_tarik'],
                "harga_modal" => $d['nilai_lepas_tarik'],
                "harga_modal" => 0,
                "tarikh_jual" => date('Y-m-d')
            );
            $this->table_ambil_emas->PutEmasProsesJual($input);
        endforeach;
    }

    private function CreateBakiEmasTarik($data){
        $input = array(
            'cawangan_id'   => $data['cawangan_id'],
            'status_tarik'  => 'SIAP TARIK',
            'tarikh_hantar' => date('Y-m-d'),
            'berat_selepas_tarik' => $data['berat_']
        );
    }

    protected function PostBuatJualanEmas(){
        $data = $this->data;
        foreach($data as $d):
            $input = array(
                "cawangan_id"=> $d['cawangan_id'],
                "tarikh_jual"=> date('Y-m-d'),
                "status_jual" => "SEDIA JUAL",
                "berat_jual"=> $d['berat_nak_jual'],
                "harga_modal"=> $d['nilai_selepas_tarik'],
                "harga_jual"=> 0,
                "untung"=> 0,
                "no_resit_jualan" => 0
            );
            $this->table_ambil_emas->CreateSediaJual($input);
            if ($d['baki_emas'] > 0 ):

            endif;
        endforeach;
    }

    protected function GetBeratUntukJual(){
        $list = $this->table_ambil_emas->ReadEmasUntukJual('SEDIA JUAL');
        $total_berat = array_sum(array_column($list, 'berat_jual'));
        return array(
            "itemize" => $list,
            "summary" => array(
                "berat_jual" => $total_berat
            )
        );
    }
}