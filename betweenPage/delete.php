<?php
require_once('../basic/basicPage.php');
if (empty($_POST['user_id'])) {
    header("Location: ../home.php");
    exit();
}
$userId = $_POST['user_id'];

try {
    $stmt1 = $conn->prepare("UPDATE photo SET visible = 0 WHERE user_id = ?");
    $stmt1->execute([$userId]);

    $conn->commit();
} catch (Throwable $e) {
    error_log('Failed to hide photos for user ' . $userId . ': ' . $e->getMessage());
} finally {
    // Always send the user back to home
    header("Location: ../home.php");
}
