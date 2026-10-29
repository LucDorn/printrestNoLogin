<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once  'src/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$servername = $_ENV['SERVERNAME'];
$username = $_ENV['LOCATION'];
$password = $_ENV['PASSWORD'];
$dbname = $_ENV['DBNAME'];

$conn = new mysqli($servername, $username, $password, $dbname);
