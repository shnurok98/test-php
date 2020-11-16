<?php

class Route {
	static function start()
	{
	
		$uri =  $_SERVER['REQUEST_URI'];
		$posPar = strpos($uri, '?');
		
		if ( $posPar) {
			// Параметры ?foo=1&bar=2 без знака вопроса
			$params = substr($uri, ($posPar + 1) );
			$uri = substr($uri, 0, $posPar);

			// Параметры в виде [0] => 'foo=1', [1] => 'bar=2'
			$params = explode('&', $params);
			// Параметры в ассоциативный массив
			for ($i = 0; $i < count($params); $i++) {
				$kv = explode('=', $params[$i]);
				$tempArr[$kv[0]] = $kv[1];
			}
			$params = $tempArr;
			
		}

		// Части uri /controller/some вида [0] => '', [1] => 'controller', [2] => 'some'
		$uri = explode('/', $uri);

		// echo 'uri:  ';
		// print_r($uri);

		// echo '<br>params:  ';
		// print_r($params);

		$method = $_SERVER['REQUEST_METHOD'];

		// получаем имя контроллера
		if ( !empty($uri[1]) ) {	
			$controller = $uri[1];
		}

		$model = $controller;
		// Название метода контроллера
		$action = 'act'.$method;


		// Подключаем модель
		$modelPath = "app/models/" . strtolower($model) . '.php';
		if(file_exists($modelPath)){
			include $modelPath;
		}

		// Подключаем контроллер
		$controllerPath = "app/controllers/" . strtolower($controller) . '.php';
		if(file_exists($controllerPath)) {
			include $controllerPath;
		} else {
			Route::ErrorPage404();
			return;
		}
		
		// Здесь перечислены все доступные контроллеры
		switch ( strtolower($controller) ) {
			case 'article':
				$controller = new Controllers\Article;
				break;
			case 'product':
				$controller = new Controllers\Product;
				break;
			default:
				Route::ErrorPage404();
				return;
				break;
		}

		// Вызываем действие контроллера
		if( method_exists($controller, $action) ) {
			// Если был передан id (/articles/:id)
			if ( !empty($uri[2]) && is_numeric( $uri[2]) ) {
				$id = (int) $uri[2];
				$controller->$action($id);
			} elseif ($params) {
				$controller->$action(NULL, $params);
			} else {
				$controller->$action();
			}
			
		} else {
			Route::ErrorPage404();
			return;
		}
	
	}
	
	static function ErrorPage404(){
		$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
		http_response_code(404);
		// header('HTTP/1.1 404 Not Found');
		// header("Status: 404 Not Found");
		// header('Location:'.$host.'404');
	}
}