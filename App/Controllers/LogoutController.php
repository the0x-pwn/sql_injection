<?php

namespace App\Controllers;

class LogoutController
{
    public function logout()
    {
        session_destroy();
        header('Location:' . '/');
        exit();
    }
}
