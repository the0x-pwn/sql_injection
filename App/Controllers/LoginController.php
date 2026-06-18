<?php

namespace App\Controllers;

use App\Models\UsersModel;

class LoginController
{
    public function login()
    {
        $username = request()->input("username");
        $password = request()->input("password");

        $user = UsersModel::login($username, $password);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['login'] = 'true';
            $_SESSION['id'] = $user['id'];

            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'You have been logged in successfully'];

            header('Location:' . '/profile?user_id=' . $user['id']);

            exit();
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'invalid username or password'];

            header('Location:' . $_SERVER["HTTP_REFERER"]);

            exit();
        }
    }
}
