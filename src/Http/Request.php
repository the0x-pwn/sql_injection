<?php

namespace Src\Http;

class Request
{
    public function method(): string
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    public function path()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = $path !== '/' ?  rtrim($path, '/') : $path;
        return str_contains($path, '?') ? explode('?', $path)[0] : $path;
    }

    public function all(): array
    {
        return $this->method() === 'post' ? $_POST : $_GET;
    }

    public function input($key)
    {
        return $this->all()[$key];
    }
}
