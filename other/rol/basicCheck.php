<?
// basic php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set("display_errors", 1);

$servername = "localhost";
$location = "root";
$password = "root";
$dbname = "roltest";

$conn = new mysqli($servername, $location, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (empty($id) || empty($name) || empty($password)) {
    header("Location: login.php");
    exit();
}
// 0 = banned 
// 1 = user
// 5 = editor
// 9 = admin
$stmt1 = $conn->prepare("SELECT role FROM users WHERE user_id = ? AND name = ? AND password = ?");
$stmt1->bind_param("iss", $id, $name, $password);
$stmt1->execute();

$result = $stmt1->get_result();
$row = $result->fetch_assoc();

if (empty($row) || $row['result'] === 0) {
    header("Location: login.php");
    exit();
}

$role = $row['role'];
