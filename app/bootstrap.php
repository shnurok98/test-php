<?php

require_once 'core/db.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
require_once 'core/initDb.php';

header("Access-Control-Allow-Origin: *"); // для CORS
// header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: text/html; charset=UTF-8");

Route::start(); // запускаем маршрутизатор