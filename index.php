<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'core/Database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';

$router = new Router();
$router->addRoute('/', 'HomeController@landing');
$router->addRoute('/app', 'HomeController@index');
$router->addRoute('/upload', 'FileController@uploadForm');
$router->addRoute('/save', 'FileController@saveFile');
$router->addRoute('/view', 'FileController@viewFile');
$router->addRoute('/preview', 'FileController@previewFile');
$router->addRoute('/edit', 'FileController@editForm');
$router->addRoute('/update', 'FileController@updateFile');
$router->addRoute('/delete', 'FileController@deleteFile');
$router->addRoute('/category', 'FileController@filterByCategory');

$router->route(); 