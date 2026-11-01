<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$pass = "root";
$db = "f2";

$connection = new mysqli($host, $user, $pass, $db);
