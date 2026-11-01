<?
require_once('../basic/basicPage.php');
if (empty($_SESSION['user_id'])) {
    header("Location: ../home.php");
    exit();
}
$userId = $_POST['user_id'];

try {
    $stmt1 = $conn->prepare("UPDATE photo SET name = ?, location = ? WHERE id = ? ");
    $stmt1->bind_param("sssi", $name, $location, $id);
    $stmt1->execute();
} catch (Throwable $e) {
    error_log('Failed to edit photos for user ' . $userId . ': ' . $e->getMessage());
} finally {
    session_destroy();
    header("Location: ../home.php");
}
