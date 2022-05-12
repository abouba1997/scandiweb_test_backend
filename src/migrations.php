<?php

/**
 * User: sangtech
 * Date: 5/1/2022
 * Time: 12:32 PM
 */

include_once __DIR__ . './../vendor/autoload.php';

use Sangtech\Scandiweb\Config\DotEnv;
use Sangtech\Scandiweb\Core\Application;

(new DotEnv(__DIR__ . './.env'))->load();

$app = new Application();

$db = Application::getDB();

$db->applyMigrations();