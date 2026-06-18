<?php

namespace App\Models;

class NotesModel extends BaseModel
{

    public static function CreateNote($note, $id): bool
    {
        static::$db = getConnectDatabase();
        $note = str_replace("'", "\'", $note);
        static::$query = "INSERT INTO " . (is_null(static::$table) ? static::getClass(static::class) : static::$table) . "(`user_id`,`note`) VALUES ('$id', '$note')";
        try {
            $stmt = static::$db->exec(static::$query); //code...
        } catch (\PDOException $th) {
            //throw $th;
        }
        return $stmt >  0;
    }

    public static function DeleteNote($note_id, $user_id): bool
    {
        static::$db = getConnectDatabase();
        static::$query = "DELETE FROM " . (is_null(static::$table) ? static::getClass(static::class) : static::$table) . " WHERE id = '{$note_id}' AND user_id = '{$user_id}'";
        $stmt = static::$db->query(static::$query);
        return $stmt->rowCount() > 0;
    }

    public static function UpdateNote($note, $note_id): bool
    {
        static::$db = getConnectDatabase();
        static::$query = "UPDATE " . (is_null(static::$table) ? static::getClass(static::class) : static::$table) . " SET note = '{$note}' WHERE id = '{$note_id}'";
        try {
            $stmt = static::$db->exec(static::$query);
        } catch (\PDOException $th) {
            //
        }
        return $stmt > 0;
    }
}
