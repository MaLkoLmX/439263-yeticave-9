<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

session_start();

if ($_SESSION) {
    $user = $_SESSION["user"];
}

$id = $user["id"];

$sql = "SELECT l.image as image, l.name as name, u.contact as contact, c.name as category, l.date_finish as date_finish, l.price as price, r.date_rate as date_create, r.amount as amount, r.id_lot as id_lot, l.id_winner as winner FROM rate r
        LEFT OUTER JOIN lot l ON l.id = r.id_lot
        LEFT OUTER JOIN user u ON l.id_winner = u.id
        JOIN categories c ON l.id_category = c.id
        WHERE r.id_user = $id
        ORDER BY date_rate DESC";

if ($result = mysqli_query($link, $sql)) {
    $rate = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    http_response_code(404);
    $page_content = include_template("error.php", [
        "categories" => get_categories($link),
        "error_title" => "Ошибка 404",
        "error" => "Страницы не найдена"
    ]);
}

$page_content = include_template("my-bets.php", [
    "categories" => get_categories($link),
    "rate" => $rate
]);

$rate_content = include_template("layout.php", [
    "categories" => get_categories($link),
    "content" => $page_content,
    "user_name" => $user["name"],
    "title" => "Мои ставки"
]);

print($rate_content);
?>
