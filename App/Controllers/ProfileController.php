<?php

namespace App\Controllers;

use App\Models\NotesModel;
use App\Models\UsersModel;

class ProfileController
{
    public function index(): void
    {
        $user_id = $_GET['user_id'];
        $user = UsersModel::all()->find($user_id)->first();

        try {
            $notes = NotesModel::all()->where('user_id', '=', $user_id)->get();
        } catch (\Exception $e) {
            $notes = [];
        }

        view('home.profile', ['user' => $user, 'notes' => $notes]);
    }
}
