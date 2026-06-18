<?php

namespace App\Models;

use PDO;

class BaseModel
{
    protected static ?string $table = null;
    protected static $instance;
    protected static string $query;
    public static ?PDO $db = null;



    protected static function getClass(string $class): string
    {
        $class = explode('\\', $class);
        $class = end($class);
        $class = str_contains($class, 'Model') ? explode('Model', $class)[0] : $class;
        return strtolower($class);
    }
    public static function all()
    {
        static::$db =  getConnectDatabase();
        static::$query = 'SELECT * FROM ' . (is_null(static::$table) ? static::getClass(static::class) : static::$table);
        static::$instance = new static();
        return static::$instance;
    }

    public static function query(string $query)
    {
        static::$db = getConnectDatabase();
        static::$query =  $query;
        static::$instance = new static();
        return static::$instance;
    }

    public static function create(array $data): bool
    {
        static::$db = getConnectDatabase();
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn($v) => "'" . $v . "'", array_values($data)));
        static::$query = 'INSERT INTO ' . (is_null(static::$table) ? static::getClass(static::class) : static::$table) . " ({$columns}) VALUES ({$values})";
        $stmt = static::$db->query(static::$query);
        return $stmt->rowCount() > 0;
    }

    public static function UpdatePassword($password, $id): bool
    {
        static::$db = getConnectDatabase();
        static::$query = "UPDATE " . (is_null(static::$table) ? static::getClass(static::class) : static::$table) . " SET password = '{$password}' WHERE id = {$id}";
        $stmt = static::$db->exec(static::$query);
        return $stmt > 0;
    }

    public function where(string $columns, string $por, string|int $value)
    {
        static::$query .= ' WHERE ' . $columns . ' ' . $por . ' ' . $value;
        return $this;
    }

    public function find(string|int $id)
    {
        static::$query .= ' WHERE id = ' . $id;
        return $this;
    }

    public function get()
    {
        $stmt = static::$db->query(static::$query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first()
    {
        $stmt = static::$db->query(static::$query);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
