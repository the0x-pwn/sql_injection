<?php

namespace Src\Database;

use PDO;
use PDOException;

class Connect
{
    protected ?PDO $db = null;

    public function __construct()
    {
        $dns = "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_NAME') . ";charset=" . env('DB_CHARSET');

        try {
            $this->db = PDO::connect($dns, env('DB_USERNAME'), env('DB_PASSWORD'), [
                PDO::ATTR_EMULATE_PREPARES => true,
                \Pdo\Mysql::ATTR_MULTI_STATEMENTS => true,
            ]);
        } catch (PDOException $e) {
            echo 'Error Connect Database' . $e->getMessage();
            exit();
        }
    }

    public function getConnect(): ?PDO
    {
        return $this->db;
    }
}
