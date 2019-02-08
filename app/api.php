<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once('./db.php');
include_once('./class/user.php');
include_once('./class/shop.php');

$response = false;

$data = @$_REQUEST['data'];

$method = @$data['method'];

$request_ip = $_SERVER['REMOTE_ADDR'];

if(is_file('./methods/'.$method.'.php')) {	
	include_once('./methods/'.$method.'.php');	
}
//API RESPONSE

header('Expires: 0');
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');

header('Content-Type: application/javascript; charset=utf-8');

echo json_encode(array('result' => $response));

?>