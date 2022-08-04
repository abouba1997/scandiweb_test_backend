<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Sangtech\Scandiweb\Core\Application;
use Sangtech\Scandiweb\Config\DotEnv;
use Sangtech\Scandiweb\Controllers\MainController;

(new DotEnv(__DIR__ . '/.env'))->load();

$app = new Application();

// CORS (without) the application get CORS error in frontend
$app->setHeaders();

// Creating routes
$app->get('/', [MainController::class, 'index']);
$app->get('/products', [MainController::class, 'products']);
$app->post('/product/create', [MainController::class, 'create']);

// Deleting product (Actually implemented by a post request)
$app->post('/products/delete', [MainController::class, 'delete']);

$app->run();
