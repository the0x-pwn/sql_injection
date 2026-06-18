<?php

namespace App\Models;

class UsersModel extends BaseModel
{
    public static function login($username, $password)
    {
        static::$db = getConnectDatabase();
        $table = (is_null(static::$table) ? static::getClass(static::class) : static::$table);
        $stmt = static::$db->query("SELECT * FROM {$table} WHERE username = '{$username}' AND password = '{$password}'");
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
