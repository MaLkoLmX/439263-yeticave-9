<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

session_start();

$categories = [];
$rate = [];
$id = $user["id"];
if ($_SESSION) {
    $user = $_SESSION["user"];
}

$sql = "SELECT id, name FROM categories";
$result = mysqli_query($link, $sql);
if (!$link) {
    header("Location: /404.php");
    die();
} else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $page_content = include_template("add-lot.php", ["categories" => $categories]);
    } else {
        http_response_code(404);
        $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 404", "error" => "Страницы не найдена"]);
    }
}

$sql = "SELECT l.image as image, l.name as name, u.contact as contact, c.name as category, l.date_finish as date_finish, l.price as price, r.date_rate as date_create FROM lot l
        LEFT OUTER JOIN rate r ON l.id = r.id_lot
        LEFT OUTER JOIN user u ON l.id_winner = u.id
        JOIN categories c ON r.id_lot = c.id
        WHERE r.id_user = $id
        ORDER BY date_rate DESC;"

if ($result_rate = mysqli_query($link, $sql)) {
    $rate = mysqli_fetch_all($result_rate, MYSQLI_ASSOC);
} else {
    http_response_code(404);
    $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 404", "error" => "Страницы не найдена"]);
}

$page_content = include_template("my-bets.php", [
    "categories" => $categories,
    "rate" => $rate
]);

$rate_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "user_name" => $user["name"],
    "title" => "Мои ставки"
]);

print($rate_content);
?>
