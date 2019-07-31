<?php

class Common_Controller {

    protected $api_ref;
    protected $data;
    protected $header;
    protected $auth;

    public function init() {
        $request_method = $_SERVER["REQUEST_METHOD"];
        $request = $_SERVER['QUERY_STRING'];
        $params = explode('/', $_SERVER['REQUEST_URI']);
        $this->header = apache_request_headers();
        $this->_restConstruct($request_method);
        $class_method = (isset($params[URIPARAM + 2])) ? explode('?', $params[URIPARAM + 2]) : null;
        $this->api_ref = array(
            'method' => $request_method,
            'params' => $this->_paramsValue($request),
            'class' => (isset($params[URIPARAM + 1])) ? $params[URIPARAM + 1] : false,
            'class_method' => (isset($params[URIPARAM + 2])) ? $this->ConstructMethodName($class_method[0]) : null
        );
//        $this->auth = Jwt_Utils::decode($this->header['Authorization'], STATELESS_SECRET);
    }

    private function _paramsValue($request) {
        $att = array();
        if (!$request == null):
            $parsed = explode('&', $request);
            foreach ($parsed as $parse):
                $an = explode('=', $parse);
                $att[$an[0]] = $an[1];
            endforeach;
        endif;
        return $att;
    }

    private function _restConstruct($method) {
        switch (strtoupper($method)):
            case 'POST':
                $this->data = $this->HeaderContentType($this->header['Content-Type']);
                break;
            case 'PUT':
                $this->data = json_decode(file_get_contents('php://input'), true);
                break;
            case 'DELETE':
                $this->data = json_decode(file_get_contents('php://input'), true);
                break;
            default:
                break;
        endswitch;
    }

    private function _requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Method Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    protected function JSONResponse($class_method) {
        $this->api_ref['method'];
        header("Content-Type: application/json;charset=utf-8");
        $json = '';
        switch ($this->api_ref['method']):
            case 'GET':
                $json = $this->ReturnJSON($class_method, $status = 200);
                break;
            case 'POST':
                $json = $this->PostCompleted($class_method);
                break;
            case 'PUT':
                $json = $this->PutCompleted($class_method);
                break;
            case 'DELETE':
                $json = $this->DeleteCompleted($class_method);
                break;
        endswitch;
        echo json_encode($json);
    }

    protected function ReturnJSON($data) {
        $result = array(
            'status' => 200,
            'total' => count($data),
            'result' => $data,
        );
        return $result;
    }

    protected function PostCompleted($data) {
        if (isset($data['error'])):
            $result = array(
                'error' => true,
                'status' => '200',
                'message' => $data['message']
            );
        else:
            $result = array(
                'status' => 200,
                "message" => "post completed!!",
                "response" => $data
            );
        endif;
        return $result;
    }

    protected function PutCompleted($data) {
        $result = array(
            'status' => 200,
            "message" => "Information is updated!!",
            "response" => $data
        );
        return $result;
    }

    protected function DeleteCompleted($data) {
        $result = array(
            'status' => 200,
            "message" => "Information is deleted!!",
            "response" => $data
        );
        return $result;
    }

    protected function ReturnError($status = 404) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        header("Content-Type: application/json;charset=utf-8");
        $result = array(
            'status' => $status,
            'error' => $this->_requestStatus($status)
        );
        echo json_encode($result);
    }

    private function ConstructMethodName($method) {
        $cleanText = ucwords(str_replace('-', ' ', $method));
        $cleanText2 = ucwords(str_replace('_', ' ', $cleanText));
        return str_replace(' ', '', $cleanText2);
    }

    protected function ConstructLink($url_data) {
        $base_url = API_URI . $this->api_ref['class'] . $url_data;
        return $base_url;
    }

    protected function HeaderContentType($content_type) {
        $content = explode(';', $content_type);
        switch ($content[0]):
            case 'application/x-www-form-urlencoded';
                $body = json_decode(file_get_contents('php://input'), true);
                break;
            case 'multipart/form-data':
                $body['files'] = $_FILES;
                $body['data'] = $_REQUEST;
                break;
            default:
                $body = json_decode(file_get_contents('php://input'), true);
                break;
        endswitch;
        return $body;
    }

    protected function DbDate($date) {
        $formatted = explode('/', $date);
        return $formatted[2] . '-' . $formatted[1] . '-' . $formatted[0];
    }

    protected function RequestInfo() {
        $ip = getenv('HTTP_CLIENT_IP') ?:
                getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                getenv('HTTP_FORWARDED_FOR') ?:
                getenv('HTTP_FORWARDED') ?:
                getenv('REMOTE_ADDR');
        return $ip;
    }
    
    protected function AuthenticateUser($user){
        $payload = array(
            'username' => $user,
            'user_ip' => $this->RequestInfo()
        );
        $key = 'ZAKV2JWTAUTH';
        $token = Jwt_Utils::encode($payload, $key);
        $user_session = new Users_Model();
        $user_session->AddUserSession($payload['username'], $payload['user_ip'], $token);
        return $token;
    }

    protected function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    protected function summary_array($myArray){
        $sumArray = array();
        foreach ($myArray as $k=>$subArray) {
        foreach ($subArray as $id=>$value) {
            $sumArray[$id]+=$value;
            }
        }
        return $sumArray;
    }
}
