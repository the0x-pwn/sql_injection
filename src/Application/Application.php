<?php

namespace Src\Application;

session_start();
session_regenerate_id(true);

use Dotenv\Dotenv;
use PDO;
use Src\Database\Connect;
use Src\Http\Request;
use Src\Http\Response;
use Src\Http\Route;

class Application
{
    protected Request $request;
    protected Response $response;
    protected Route $route;
    protected Connect $db;

    public function __construct()
    {
        Dotenv::createImmutable(base_path())->load();
        $this->request = new Request();
        $this->response = new Response();
        $this->route = new Route($this->request, $this->response);
        $this->db = new Connect();
    }

    public function run()
    {
        return $this->route->resolver();
    }

    public function getConnectDb(): ?PDO
    {
        return $this->db->getConnect();
    }
}
