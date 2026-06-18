<?php


ini_set('log_errors', 0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once base_path() . 'Routes/web.php';


app()->run();
