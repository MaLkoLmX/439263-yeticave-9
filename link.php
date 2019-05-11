<?php
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect("127.0.0.1", "root", "", "yaticave");/*подключение к СУБД*/
mysqli_set_charset($link, "utf8");
if ($link == false) {
    $error = mysqli_connect_error();
    $content = include_template("404.html", ["error" => $error]);
    print($content);
}

$is_auth = rand(0, 1);

$user_name = "Кадиров Сергей"; // укажите здесь ваше имя

$index = 0;
$num_count = count($categories);
?>
