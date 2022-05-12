<?php

namespace Sangtech\Scandiweb\Core;

/**
 * Class Application
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

class Application
{
    protected Router $router;
    protected Request $request;
    protected Response $response;
    protected static Database $db;

    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
        $this->response = new Response();
        static::$db = new Database();
    }

    public function get($path, $callback): void
    {
        $this->router->get($path, $callback);
    }

    public function post($path, $callback): void
    {
        $this->router->post($path, $callback);
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }

    public static function getDB(): Database
    {
        return static::$db;
    }

    public function setHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == "OPTIONS") {
            header('HTTP/1.1 200 OK');
        }
    }
}
