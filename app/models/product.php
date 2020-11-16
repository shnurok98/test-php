<?php
namespace Models;

use Core\Db;

class Product{
	private $id;
	private $name;
	private $href;
	private $tableName = "products";

	public function __construct(){
		$db = new Db();
		$this->conn = $db->getconnection();
	}


	public function getAll() {

		$q = "SELECT * FROM `$this->tableName` ;";
		
		$res = $this->conn->query($q);
		// $res->execute();
		if ($res !== FALSE) {
				$res = $res->fetchAll(\PDO::FETCH_ASSOC);
				return $res;
		} else {
				$this->$conn->errorInfo();
		}
	}
}