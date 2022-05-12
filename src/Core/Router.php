<?php

namespace Sangtech\Scandiweb\Core;

/**
 * Class Router
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

class Router
{
    protected Request $request;
    protected Response $response;
    protected array $routes = [];

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            return "404 Page not found!";
        }

        if(is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}
