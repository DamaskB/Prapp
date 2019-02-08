<?
Class BS_Shop
{
	var $module_name = 'bs_shop';


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
				`card_id` INT,
				`summ` real,
				`created_at` TIMESTAMP,
				 PRIMARY KEY (`id`)
			)'
		);

		$this->DB->exec(
			'CREATE TABLE IF NOT EXISTS bs_shop_items (
				`id` INT AUTO_INCREMENT,
				`shop_id` INT NOT NULL,
				`item` VARCHAR(255),
				`count` INT,
				`price` real,
				 PRIMARY KEY (`id`)
			)'
		);

		$this->DB->ActivateModule($this->module_name);
	}

	public function GetCardSumm($id = NULL)
	{
		$add_where = '';

		$card = [];

		$plc = [date('Y-m-d H:i:s', time() - 60*60*24*30)];

		if ($id != NULL) {
			$add_where .= ' AND card_id = ?';
			$plc[] = $id;
		}

		$q = 'SELECT SUM(summ) AS total, card_id FROM bs_shop WHERE created_at >= ? ' . $add_where . ' GROUP BY card_id';

		$raw = $this->DB->ExecQuery($q, $plc);

		if ($raw != NULL) {
			foreach ($raw as $v) {
				$card[$v['card_id']] = $v['total'];
			}
		}

		return $card;
	}

	public function NewShop($data)
	{
		$q = 'INSERT INTO ' . $this->module_name . ' VALUES (0,?,?,?)';

		$shop_id = $this->DB->ExecQuery($q, [
			$data['card_id'],
			$data['summ'],
			$data['created_at']
		], 3);

		foreach ($data['items'] as $v) {
			$v['shop_id'] = $shop_id;
			$this->AddShopItem($v);
		}
	}

	public function AddShopItem($data)
	{
		$this->DB->ExecQuery('INSERT INTO bs_shop_items VALUES(0,?,?,?,?)', [
			$data['shop_id'],
			$data['item'],
			$data['count'],
			$data['price']
		], 0);
	}

	public function FillShopAndItems($card_ids, $c_shop, $c_items)
	{
		$cc = count($card_ids);
	
		if ($cc) {
			for ($i=0; $i < $c_shop; $i++) {
				$items = [];
				$summ = 0;

				for ($j=0; $j < $c_items; $j++) { 
					$items[$j] = [
						'item' => RandomString(12),
						'count' => rand(1, 10),
						'price' => rand(1, 10000)
					];

					$summ += $items[$j]['count']*$items[$j]['price'];
				}

				$this->NewShop([
					'card_id' => $card_ids[rand(0, $cc - 1)],
					'summ' => $summ,
					'created_at' => date('Y-m-d H:i:s', time() - rand(0, 10000)),
					'items' => $items			
				]);
			}
		}
	}
}