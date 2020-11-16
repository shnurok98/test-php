<?php
namespace Controllers;

use Core\Controller as Controller;
use Models;
use Views;

class Product extends Controller {

	function __construct()
	{
		$this->model = new Models\Product();
		// Отображение для продуктов не требуется
		// $this->view = new Views\View();
	}
	
	/**
	 * Контроллер для методов GET
	 * Тут отдаю json т.к. по факту у продукта нету вью
	 */
	function actGET(int $id = NULL, array $params = NULL) {
		$data = $this->model->getAll();	
		$data = json_encode($data);
		header("Content-Type: application/json; charset=UTF-8");
		echo $data;
	}

}