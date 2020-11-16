<?php
namespace Core;

class Table {

	public $tableName;
	public $conn;

	function __construct($conn, $tableName){
		$this->conn = $conn;
		$this->tableName = $tableName;
	}

	/**
	 * Принимает на вход массив с колонками и их типами
	 */
	function createTable(array $arr){
		$query = 'CREATE TABLE IF NOT EXISTS ';
		$query .= $this->tableName . ' ( `id` int(11) PRIMARY KEY AUTO_INCREMENT , ';
		foreach($arr as $key => $val){
			$query .= "`$key` $val NOT NULL, ";
		}
		$query = substr_replace($query, ' );', -2);

		$res = $this->conn->exec($query);
		if ($res !== FALSE) {
			// echo "Таблица $this->tableName успешно создана!";
		} else {
			print_r($this->$conn->errorInfo());
		}
	}

	/**
	 * Получает на вход массив с данными и добавляет в БД
	 */
	function insertData(array $arr){
		$query = "INSERT INTO $this->tableName (";
		$fields = implode(', ', array_keys($arr[0]));
		$query .= " $fields ) VALUES ";
		for($i = 0; $i < count($arr); $i++){
			$query .= "(";
			foreach($arr[$i] as $key => $val){
				// htmlspecialchars(strip_tags($val))
				$query .= " '" . str_replace("'", '"', $val ) . "', ";
			}
			$query = substr_replace($query, ' ),', -2);
		}
		$query = substr_replace($query, ';', -1);
		echo $query;
		
		try {
			$res = $this->conn->exec($query);
		} catch (PDOException $e) {
			echo "mes from pdo:" . $e->getMessage();
		}
	}

}

$json1 = file_get_contents(__DIR__ . '/blog.json');
$json1 = json_decode($json1, TRUE);

$json2 = file_get_contents(__DIR__ . '/products.json');
$json2 = json_decode($json2, TRUE);

$db = new Db();
$conn = $db->getconnection();

$articles = new Table($conn, 'articles');
$articles->createTable($json1['columns']);

$products = new Table($conn, 'products');
$products->createTable($json2['columns']);


// Расскомментировать для загрузки данных в БД из json
// $articles->insertData($json1['data']);
// $products->insertData($json2['data']);
