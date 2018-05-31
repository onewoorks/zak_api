<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$plainUrl = $_SERVER['SERVER_NAME'] . '/zak_api';
$params = explode('/', $_SERVER['REQUEST_URI']);

define('URIPARAM', 2);

if (isset($params[URIPARAM])):
    $p = explode('?', $params[URIPARAM + 1]);
    $classController = $p[0];
else:
    $classController = 'login';
endif;

$target = './controllers/' . $classController . '.php';

define('DIR_ROOT', '/Users/lydiairwan/Sites/zak_api');
define('SITE_ROOT', $plainUrl . '/');
define('API_URI', $plainUrl . '/api/' . $params[URIPARAM] . '/');
define('STATELESS_SECRET', 'thisIsMyCode');
define('IMAGE_DIR', __DIR__ . '/images');
$api_access = true;

function __autoload($classname) {
    list($suffix, $filename) = preg_split('/_/', strrev($classname), 2);
    $filename = strrev($filename);
    $suffix = strrev($suffix);
    switch (strtolower($suffix)) {
        case 'controller':
            $folder = '/api/controllers/';
            break;
        case 'model':
            $folder = "/models/";
            break;
        case 'driver':
            $folder = "/libraries/drivers/";
            break;
        case 'utils':
            $folder = "/libraries/utils/";
            break;
    }

    $file = DIR_ROOT . $folder . strtolower($filename) . '.php';

    if (file_exists($file)) {
        include_once($file);
    } else {
        die("File '$filename' containing class '$classname' not found in '$folder'.");
    }
}

//include_once DIR_ROOT . '/libraries/utils/format.php';

$header = apache_request_headers();


//if (isset($header['Authorization'])):
//    $explode = Jwt_Utils::decode($header['Authorization'], STATELESS_SECRET);
//    $api_access = authorize($explode); 
//else:
//    header("Content-Type: application/json;charset=utf-8");
//    $auth_error = array(
//        'status' => 401,
//        'message' => 'Authorization failed!',
//        'a'=> $target
//    );
//    die(json_encode($auth_error));
//endif;

include_once DIR_ROOT . '/libraries/drivers/mysql.php';

if (file_exists($target)) {
    include_once($target);
    $class = ucfirst($classController) . '_Controller';
    if ($api_access):
        class_exists($class) ? $controller = new $class($params) : die('class does not exist!');
    else:
        $controller = new Login_Controller();
    endif;
} else {
    die($classController . ' page does not exist!');
}

function authorize($jwt) {
//    $mysql = new Mysql_Driver();
//    $mysql->ApiSqlConnection($jwt->data_pointer->database[0]->database_name);
//    return true;
}
