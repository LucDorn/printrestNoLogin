<?
require_once('../basic/basicPage.php');
if (empty($_SESSION['user_id']) || empty($_POST['name'] || empty($_POST['location']))) {
    header("Location: ../home.php");
    exit();
}
$id = $_SESSION['user_id'];
$name = $_POST['name'];
$locationImg = $_POST['location'];

try {
    $stmt1 = $conn->prepare("UPDATE photo SET name = ?, location = ? WHERE user_id = ? ");
    $stmt1->bind_param("ssi", $name, $locationImg, $id);
    $stmt1->execute();
} catch (Throwable $e) {
    error_log('Failed to edit photos for user ' . $id . ': ' . $e->getMessage());
} finally {
    session_destroy();
    header("Location: ../list.php");
}
