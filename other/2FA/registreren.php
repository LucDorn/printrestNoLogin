<?php
require './basic.php';

if ($_POST['wachtwoord'] === $_POST['herhaal'] && isset($_POST['gebruikersnaam']) & isset($_POST['email'])) {
    $hashed_password = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);


    $stmt = $connection->prepare("INSERT INTO Gebruiker VALUES (null, ? , 259045909371948501892847591048501495, ?, 0, null, ?);");
    $stmt->bind_param("sss", $_POST['gebruikersnaam'], $hashed_password, $email);
    $stmt->execute();
    $stmt->store_result();
    $_SESSION['user'] = $_POST['gebruikersnaam'];
    header('Location: ../next.php');
} else {
    header('Location: ../start.php');
    exit();
}
