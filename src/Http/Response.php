<?php

namespace Src\Http;

class Response
{
    public function back(?string $url = null, int $code = 303)
    {
        http_response_code($code);
        header("Location:" . (!is_null($url) ? $url : $_SERVER['HTTP_REFERER']));
        exit();
    }
}
