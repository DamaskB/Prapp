<?
$users = new BS_User();

switch ($data['action']) {
	case 'NewUser':
		$err = [];

		if ($data['info']['name'] == NULL)
			$err[] = 'name';
		if ($data['info']['last_name'] == NULL)
			$err[] = 'last_name';
		if ($data['info']['email'] == NULL OR !filter_var($data['info']['email'], FILTER_VALIDATE_EMAIL))
			$err[] = 'email';

		if ($err == NULL) {
			$users->NewUser($data['info']);

			$response['function'] = 'render_user_list';
			$response['params'] = $users->GetUserList();

			$response['update_function'] = 'fill_free_cards';
			$response['update_params'] = $users->GetFreeCards();
		}
		else {
			$response['function'] = 'show_form_error';
			$response['params'] = $err;
		}
		
		break;
	
	case 'FixCard':

		if (!$users->GetCardOwner()) {
			$users->FixCard($data['owner_id'], $data['card_id']);
		}

		$response['function'] = 'render_user_cards';
		$response['params'] = $users->GetUserCards($data['owner_id']);

		break;

	case 'GetUserList':

		$response['function'] = 'render_user_list';
		$response['params'] = $users->GetUserList(@$data['name'], @$data['last_name'], @$data['card_number']);

		$response['update_function'] = 'fill_free_cards';
		$response['update_params'] = $users->GetFreeCards();

		break;

	case 'GetReports':

		$top_list = [];

		$list = $users->GetTopUsers(@$data['count']);

		if ($list != NULL) {
			foreach ($list as $k => $v) {
				$info = $users->GetUserInfo($k);
				$info['summ'] = number_format($v, 0, '.', ' ');
				$top_list[] = $info;
			}
		}

		$response['function'] = 'render_top_list';
		$response['params']['users'] = $top_list;
		$response['params']['users_count'] = $users->ReportUsersCount();
		$response['params']['cards_count'] = $users->ReportCardsCount();

		break;

	case 'GetUserInfo':

		$response['function'] = 'render_user_edit';
		$response['params'] = $users->GetUserInfo($data['id']);

		break;

	case 'FillDB';

		$users->FillDB();
		$response['function'] = 'render_user_list';
		$response['params'] = $users->GetUserList();

		$response['update_function'] = 'fill_free_cards';
		$response['update_params'] = $users->GetFreeCards();

		break;

	case 'GetFreeCards':

		$response['function'] = 'fill_free_cards';
		$response['params'] = $users->GetFreeCards();

		break;

	default:
		
		break;
}