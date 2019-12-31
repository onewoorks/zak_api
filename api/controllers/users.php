<?php
 
class Users_Controller extends Common_Controller {

    private $users_table;

    public function __construct() {
        $this->init();
        $api = $this->api_ref;
        $method = ucwords(strtolower($api['method']));
        $method_name = ($api['class_method'] == null) ? $method . 'AllUsers' : $method . ($api['class_method']);
        $this->users_table = new Users_Model();
        return (method_exists($this, $method_name)) ? $this->JSONResponse($this->$method_name($api['params'])) : $this->ReturnError();
    }
    
    protected function PostLogin(){
        $input = $this->data;
        $isUser = $this->users_table->ReadUser($input['username'], $input['password']);
        $token = false;
        if($isUser):
            $user = $isUser['id'];
            $token = $this->AuthenticateUser($user);
        endif;
        return array(
            'id' => $isUser['id'],
            'username' => $isUser['username'],
            'full_name' => $isUser['full_name'],
            'token'=>$token);
    }

    protected function PostJualan() {
        $jualan = $this->data;
        $nobil = $this->transaksi_jualan_table->GetCurrentNoBilAndUpdate();
        foreach ($jualan as $j):
            $data = array(
                'cawangan' => $j['cawangan'],
                'tarikh' => $this->DbDate($j['tarikh']),
                'perkara' => $j['perkara'],
                'market' => $j['market'],
                'tolak' => $j['tolak'],
                'berat' => $j['berat'],
                'gst' => $j['gst'],
                'hargaGst' => $j['hargaGst'],
                'harga' => $j['hargaClean'],
                'nobil' => $nobil
            );
            $this->transaksi_jualan_table->CreateTransaksiJualan($data);
        endforeach;
        return array('no_resit'=>$nobil);
    }

    protected function GetJualan() {
        return $this->transaksi_jualan_table->ReadAllJualan();
    }

    protected function GetJualanResit($params) {
        $jualan = $this->transaksi_jualan_table->ReadJualanResit($params['id']);
        $maxItem = 21 - count($jualan);
        if ($maxItem > 0):
            $newMax = count($jualan) + $maxItem;
            for ($i = count($jualan); $i < $newMax; $i++):
                $jualan[$i] = array('isempty' => true);
            endfor;
        endif;
        return $jualan;
    }
    
    protected function DeleteInvoice(){
        $info = $this->data;
        $this->transaksi_jualan_table->DeleteRekodJualan($info['resit_no']);
        return true;
    }

    protected function GetSenaraiKakitangan(){
        return $this->users_table->ReadSenaraiKakitangan();
    }

    protected function PostDaftarKakitangan(){
        $kakitangan = $this->data;
        return $this->users_table->CreateKakitangan($kakitangan);
    }

    protected function DeleteKakitangan($params){
        $this->users_table->DeleteKakitangan($params['id']);
    }

    protected function PutDaftarKakitangan(){
        $data = $this->data;
        $this->users_table->UpdateKakitangan($data);
    }

}
