<?
Class BS_User {
	var  $module_name = 'bs_users';

	function __construct()
	{
		global $PDO;

		$this->DB = &$PDO;

		if (!isset($this->DB->active_modules[$this->module_name]))
			$this->Activate();
	}

	public function Activate()
	{
		$this->DB->exec(
			'CREATE TABLE IF NOT EXISTS ' . $this->module_name . ' (
				`id` INT AUTO_INCREMENT,
				`name` VARCHAR(255),
				`last_name` VARCHAR(255),
				`address` VARCHAR(255),
				`email` VARCHAR(255),
				`phone` VARCHAR(255),
				`created_at` TIMESTAMP,
				 PRIMARY KEY (`id`)
			)'
		);

		$this->DB->exec(
			'CREATE TABLE IF NOT EXISTS bs_cards (
				`id` INT AUTO_INCREMENT,
				`number` VARCHAR(255),
				`type` VARCHAR(45),
				`owner_id` INT,
				 PRIMARY KEY (`id`)
			)'
		);

		$this->DB->ActivateModule($this->module_name);
	}

	public function GetUserList($name = NULL, $last_name = NULL, $card_number = NULL)
	{
		$add_where = '';
		$where = [];

		if ($name != NULL) {
			$add_where .= ' AND name LIKE ?';
			$where[] = '%'.$name.'%';
		}
		if ($last_name != NULL) {
			$add_where .= ' AND last_name LIKE ?';
			$where[] = '%'.$last_name.'%';
		}
		if ($card_number != NULL)
			$add_where .= ' AND id IN (' . implode(',', $this->GetCardOwnersByNumber($card_number)) . ')';

		$users = $this->DB->ExecQuery('SELECT * FROM bs_users WHERE 1=1 ' . $add_where . ' ORDER BY id DESC', $where);

		$user_cards = $this->GetUserCards(NULL);

		$user_has_cards = [];

		if ($users) {
			if ($user_cards != NULL) {
				foreach ($user_cards as $v) {
					$user_has_cards[$v['owner_id']][$v['id']] = $v['number'];
				}
			}

			foreach ($users as $k => $v) {
				$v['cards'] = @$user_has_cards[$v['id']];
				$users[$k] = $v;
			}
		}

		return $users;
	}

	public function GetTopUsers($count = 10)
	{
		$top = $owner_summ = [];

		$card_raw = $this->DB->ExecQuery('SELECT * FROM bs_cards WHERE owner_id IS NOT NULL');

		$shop = new BS_Shop();

		$cards_total = $shop->GetCardSumm();

		if ($card_raw != NULL AND $cards_total != NULL) {
			foreach ($card_raw as $v) {
				if (@$owner_summ[$v['owner_id']] == NULL)
					$owner_summ[$v['owner_id']] = @$cards_total[$v['id']];
				else
					$owner_summ[$v['owner_id']] += @$cards_total[$v['id']];
			}
		}

		arsort($owner_summ);
		
		return array_slice($owner_summ, 0, $count, true);

	}

	public function GetUserInfo($id)
	{
		return $this->DB->ExecQuery('SELECT * FROM bs_users WHERE id = ?', [$id], 1);
	}

	public function NewUser($data)
	{
		$q = 'INSERT INTO ' . $this->module_name. ' VALUES (0,?,?,?,?,?,NOW())';

		
		$owner_id = $this->DB->ExecQuery($q, array(
			$data['name'],
			$data['last_name'],
			$data['address'],
			$data['email'],
			$data['phone']
		), 3);

		if (isset($data['card_id']))
			$this->FixCard($owner_id, $data['card_id']);

		return $owner_id;
	}

	public function GetCardOwner($card_id)
	{
		$card_info = $this->DB->ExecQuery('SELECT * FROM bs_cards WHERE id = ?', [$card_id], 1);

		return @$card_info['owner_id'];
	}

	public function GetFreeCards()
	{
		return $this->DB->ExecQuery('SELECT * FROM bs_cards WHERE owner_id IS NULL');
	}

	public function GetCardOwnersByNumber($card_number)
	{
		$raw = $this->DB->ExecQuery('SELECT * FROM bs_cards WHERE `number` LIKE ?', ['%' . $card_number . '%']);

		$ids = [];

		if ($raw != NULL) {
			foreach ($raw as $v) {
				if ($v['owner_id'] != NULL) $ids[$v['owner_id']] = $v['owner_id'];
			}
		}

		return $ids;
	}

	public function GetUserCards($owner_id)
	{
		if ($owner_id != NULL)
			return $this->DB->ExecQuery('SELECT * FROM bs_cards WHERE owner_id = ?', [$owner_id]);
		else
			return $this->DB->ExecQuery('SELECT * FROM bs_cards WHERE owner_id IS NOT NULL');

	}

	public function FixCard($owner_id, $card_id)
	{
		$ex = $this->DB->ExecQuery('SELECT id FROM bs_cards WHERE id = ? AND owner_id IS NULL', [$card_id], 1);

		if ($ex != NULL) {

			$q = 'UPDATE bs_cards SET owner_id = ? WHERE id = ?';

			$this->DB->ExecQuery($q, array(
				$owner_id,
				$card_id
			), 0);
		}
	}

	public function NewCard($data)
	{
		$q = 'INSERT INTO bs_cards VALUES (0,?,?,NULL)';

		return $this->DB->ExecQuery($q, array(
			$data['number'],
			$data['type']
		), 3);
	}

	public function ReportUsersCount()
	{
		$raw = $this->DB->ExecQuery('SELECT COUNT(id) AS count FROM bs_users', NULL, 1);
		return $raw['count'];
	}

	public function ReportCardsCount()
	{
		$raw = $this->DB->ExecQuery('SELECT COUNT(id) AS count FROM bs_cards WHERE owner_id IS NOT NULL', NULL, 1);
		return $raw['count'];
	}

	public function FillDB()
	{
		$set = [
			'cards' => 100,
			'users' => 20,
			'shops' => 100,
			'items' => 5
		];

		$this->DB->ExecQuery('TRUNCATE TABLE bs_users');
		$this->DB->ExecQuery('TRUNCATE TABLE bs_cards');

		$this->FillCards($set['cards']);
		
		for ($i=0; $i < $set['users']; $i++) { 
			$owner_id = $this->NewUser([
				'name' => RandomString(6),
				'last_name' => RandomString(8),
				'address' => RandomString(12),
				'email' => RandomString(8) . '@'. RandomString(5) . '.' . RandomString(3),
				'phone' => '+420' . rand(100000000, 999999999),
			]);
		
			for ($j=0; $j < rand(1, 2); $j++) { 
				$this->FixCard($owner_id, rand(1, $set['cards']));
			}
		}

		$owner_id = $this->NewUser([
			'name' => 'Bulat',
			'last_name' => 'Khaziev',
			'address' => 'Kazan',
			'email' => 'bulatt86@gmail.com',
			'phone' => '+79991850118',
		]);
	
		for ($j=0; $j < rand(1, 8); $j++) { 
			$this->FixCard($owner_id, rand(1, $set['cards']));
		}

		$shop = new BS_Shop();

		$raw = $this->DB->ExecQuery('SELECT id FROM bs_cards WHERE owner_id IS NOT NULL');

		$card_ids = [];

		foreach ($raw as $v) {
			$card_ids[] = $v['id'];
		}

		$shop->FillShopAndItems($card_ids, $set['shops'], $set['items']);

	}

	public function FillCards($count = 100)
	{
		for ($i=0; $i < $count; $i++) { 
			$this->DB->ExecQuery('INSERT INTO bs_cards VALUES(0,?,?,NULL)', [rand(10000000, 90000000), rand(1, 2)], 0);
		}
	}
}