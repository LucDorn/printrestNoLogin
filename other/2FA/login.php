<?php
require './basic.php';

// Check if the form was submitted
if (isset($_POST['gebruikersnaam']) && isset($_POST['wachtwoord'])) {

    $stmt = $connection->prepare("SELECT wachtwoord FROM Gebruiker WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $_POST['gebruikersnaam']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $hashed_password = $row['wachtwoord'];
        if (password_verify($_POST['wachtwoord'], $hashed_password)) {
            $_SESSION['user'] = $_POST['gebruikersnaam'];
            header('Location: ../2F.php');
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }
} else {
    $_SESSION['error'] = "Please fill in all fields.";
}
header('Location: ../start.php');
