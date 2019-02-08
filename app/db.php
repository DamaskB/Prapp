<?
function RandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';

	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}

	return $randomString;
}

require_once './../config.php';

Class MyPDO extends PDO {
	public function ExecQuery($sql, $args = NULL, $type = 2)
	{
		$prepared = $this->prepare($sql);
		$prepared->execute($args);

		switch ($type) {
			case 0:
				return NULL;
				break;
			case 1:
				return $prepared->fetch(PDO::FETCH_ASSOC);
				break;
			case 2:
				return $prepared->fetchAll(PDO::FETCH_ASSOC);
				break;
			case 3:
				return $this->lastInsertId();
			default:
				return $prepared->fetchAll(PDO::FETCH_ASSOC);
				break;
		}
	}

	public function CheckModules()
	{
		$bs_modules = $this->ExecQuery('SELECT * FROM bs_modules');
		
		if ($bs_modules == NULL) {
			$this->Activate();
			$bs_modules = $this->ExecQuery('SELECT * FROM bs_modules');
		}

		$modules = [];

		foreach ($bs_modules as $v) {
			$modules[$v['name']] = 1;
		}

		$this->active_modules = $modules;
	}

	public function ActivateModule($name)
	{
		$this->ExecQuery('INSERT INTO bs_modules VALUES (0,?)', array($name), 0);

		$this->active_modules[$name] = 1;
	}

	private function Activate()
	{
		$query =
			'CREATE TABLE IF NOT EXISTS bs_modules (
				`id` INT AUTO_INCREMENT,
				`name` VARCHAR(50),
				 PRIMARY KEY (`id`)
			);';

		$this->exec($query);

		$this->ActivateModule('main');
	}
}

try {
	$PDO = new MyPDO('mysql:port='.DB_PORT.';dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
} catch (PDOException $e) {
	echo 'Connection Error: ' . $e->getMessage();
	die();
}

$PDO->CheckModules();