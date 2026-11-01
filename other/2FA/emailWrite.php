<?php
require './basic.php';
if ($_POST['theEmailCode'] === $_SESSION['code']) {
    header('Location: ../next.php');
} else {
    header('Location: ./emailTest.php');
    exit();
}
