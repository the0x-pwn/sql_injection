<?php

namespace App\Controllers;

use App\Models\NotesModel;

class NoteController
{
    public function created()
    {
        $id = request()->input("user_id");
        $note = request()->input("note");
        if (! $note) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Input Required'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $notes = NotesModel::CreateNote($note, $id);

        if ($notes) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'The note was added successfully.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'An unexpected error occurred. Please try again.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function delete()
    {
        $note_id = request()->input('note_id');
        $user_id = request()->input('user_id');

        $delNote = NotesModel::DeleteNote($note_id, $user_id);

        if ($delNote) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'The note was deleted successfully.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'An unexpected error occurred. Please try again.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function edit()
    {
        $note_id = request()->input('note_id');
        $user_id = request()->input('user_id');
        $note = NotesModel::query("SELECT * FROM notes WHERE id = '{$note_id}' AND user_id = '{$user_id}'")->first();
        view('home.EditNote', ['note' => $note]);
    }


    public function update()
    {
        $note_id = request()->input('note_id');
        // $user_id = request()->input('user_id');
        $note = request()->input('note');

        if (! $note) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Input Required'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $upNote = NotesModel::UpdateNote($note, $note_id);

        if ($upNote) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'The note has been updated.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'An unexpected error occurred. Please try again.'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
