<?php

namespace App\Controllers;

use App\Models\UsersModel;

class RegisterController
{
    public function register()
    {
        $username = request()->input('username');
        $password = request()->input('password');
        $password_confirm = request()->input('password_confirm');

        if ($password != $password_confirm) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'The password does not match'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $user = UsersModel::create([
            'username' => $username,
            'password' => $password,
        ]);

        if ($user) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'The account has been created successfully'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
