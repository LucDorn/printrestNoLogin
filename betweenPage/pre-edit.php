<?php
session_start();

require_once('../basic/basicPage.php');

//Get the inputs from the form
$password   = $_POST['password'] ?? '';

// send back if it goes wrong
if ($password === '') {
    header("Location: ../home.php");
    exit;
}

// checks if password correct
try {
    $stmt = $conn->prepare("SELECT user_id, time FROM make_edit WHERE password = ? LIMIT 1");
    $stmt->bind_param("s", $password);
    $stmt->execute();
    $result = $stmt->get_result();


    // password not found
    if ($result->num_rows === 0) {
        header("Location: ../home.php");
        exit;
    }

    // get info out of it
    $row = $result->fetch_assoc();
    $userId    = $row['user_id'];
    $dateAdded = $row['time'];
    $stmt->close();

    // looks up information to show
    $stmt2 = $conn->prepare("SELECT name, location, fileLocation FROM photo WHERE user_id = ? AND visible = 1 LIMIT 1");
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
    $photoResult = $stmt2->get_result();
    $photo = $photoResult->fetch_assoc();
    $stmt2->close();

    // puts info in session
    $_SESSION['name']         = $photo['name']              ?? ' ';
    $_SESSION['location']     = $photo['location']          ?? ' ';
    $_SESSION['user_id']      = $userId                     ?? ' ';
    $_SESSION['fileLocation'] = $photo['fileLocation']      ?? ' ';
} catch (Throwable $e) {
    error_log('Failed to show photo to user with: ' . $password . ': ' . $e->getMessage());
} finally {
    header("Location: ../add-edit.php");
}
