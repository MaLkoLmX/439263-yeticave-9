<?php
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect("127.0.0.1", "root", "", "yaticave");
mysqli_set_charset($link, "utf8");
if (!$link) {
    header("Location: /error.php");
    die();
}

session_start();

if ($_SESSION) {
    $user = $_SESSION["user"];
}

$user_name = $user["name"];
?>
