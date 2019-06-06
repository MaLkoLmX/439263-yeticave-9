<?php
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect("127.0.0.1", "root", "", "yaticave");
mysqli_set_charset($link, "utf8");
if (!$link) {
    header("Location: /error.php");
    die();
}

if ($_SESSION) {
    $user = $_SESSION["user"];
}

$is_auth = rand(0, 1);

$user_name = "Кадиров Сергей";

$index = 0;
$num_count = count($categories);
?>
