<?php
namespace Controllers;

use Core\Controller as Controller;
use Models;
use Views;

// Делаю тут хотя бы минимальное подобие REST

class Article extends Controller {

	function __construct()
	{
		$this->model = new Models\Article();
		$this->view = new Views\View();
	}
	
	/**
	 * Контроллер для методов GET
	 */
	function actGET(int $id = NULL, array $params = NULL) {
		if ($id !== NULL) {
			// Одна запись
			$data = $this->model->getById($id);		
			$this->view->render('article-one.php', $data);
		} elseif ($params) {
			// Много записей с параметрами
			$data = $this->model->getAll($params);		
			$this->view->render('article-all.php', $data);
		} else {
			// Много записей без параметров
			$data = $this->model->getAll();		
			$this->view->render('article-all.php', $data);
		}
		
	}

	/**
	 * Контроллер для методов POST
	 */
	function actPOST() {
		// Получаем данные в формате json
		$postData = file_get_contents('php://input');
		$data = json_decode($postData, true);

		$res = $this->model->create($data);
		if ($res) {
			// Успешное создание
			http_response_code(201);
		} else {
			// Неверные или невалидные данные
			http_response_code(400);
		}

		// print_r($_POST['foo']);
	}
}