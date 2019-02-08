<?
$page = [];

$v_dir = 'app/views/';

$url = explode('-', @$_GET['p']);

if ($url == NULL) $url[0] = NULL;

switch ($url[0]) {
	case NULL:
		$page['title'] = 'Main page';
		$view = 'user_list';
		break;

	case 'user':

		$page['title'] = 'User page';
		$view = 'user_edit';

		break;

	case 'reports':

		$page['title'] = 'Top users';
		$view = 'report';

		break;
	
	default:

		break;
}
ob_start();
include $v_dir . $view . '.php';
$page['html'] = ob_get_contents();
ob_end_clean();