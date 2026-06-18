<?php

namespace App\Controllers;

use App\Models\UsersModel;

class ChangePasswordController
{
    public function update()
    {
        $password = request()->input("password");
        $id = request()->input("user_id");

        if (!$password) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Input Required'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $user = UsersModel::UpdatePassword($password, $id);

        if ($user) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Password updated successfully.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'An unexpected error occurred. Please try again.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
