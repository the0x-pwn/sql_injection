<?php

use Src\Application\Application;
use Src\Http\Request;
use Src\Http\Response;
use Src\View\View;

if (! function_exists('base_path')) {
    function base_path(): string
    {
        return dirname(__DIR__) . '/../';
    }
}

if (! function_exists('view_path')) {
    function view_path(): string
    {
        return base_path() . 'View/';
    }
}


if (! function_exists('value')) {
    function value($value): mixed
    {
        return ($value instanceof Closure) ? $value() : $value;
    }
}


if (! function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? value($default);
    }
}


if (! function_exists('app')) {
    function app(): Application
    {
        $instance = null;
        if (!$instance) {
            $instance = new Application();
        }
        return $instance;
    }
}



if (! function_exists('view')) {
    function view(string $view, array $data = [])
    {
        View::make($view, $data);
    }
}


if (! function_exists('getConnectDatabase')) {
    function getConnectDatabase(): ?PDO
    {
        return app()->getConnectDb();
    }
}

if (!function_exists('response')) {
    function response(): Response
    {
        return new Response();
    }
}

if (! function_exists('dd')) {
    function dd(mixed $data)
    {
        dump($data);
    }
}

if (! function_exists('public_path')) {
    function public_path(): string
    {
        return base_path() . 'Public/';
    }
}


if (! function_exists('request')) {
    function request(): Request
    {
        return new Request();
    }
}
