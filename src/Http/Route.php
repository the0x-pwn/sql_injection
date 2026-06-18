<?php

namespace Src\Http;

class Route
{
    protected static array $routes = [];

    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public static function get(string $route, array|callable $action): void
    {
        static::$routes['get'][$route] = $action;
    }

    public static function post(string $route, array|callable $action): void
    {
        static::$routes['post'][$route] = $action;
    }

    public function resolver(): void
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $action = static::$routes[$method][$path];

        if (is_callable($action)) {
            call_user_func_array($action, []);
        }

        if (is_array($action)) {
            [$controller, $method] = $action;
            call_user_func_array([new $controller, $method], []);
        }
    }
}
