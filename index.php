<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

session_start();

$categories = [];
$products = [];
$content = "";

if ($_SESSION) {
    $user = $_SESSION["user"]["name"];
}

$sql = "SELECT name, code FROM categories";
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $content = include_template("content.php", ["categories" => $categories]);
}
else {
    http_response_code(404);
    $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 404", "error" => "Страницы не найдена"]);
}

/*Заполняем лот*/
$sql = "SELECT l.id as id_lot, l.name, price, image, MAX(c.name) as categories, MAX(r.amount), l.date_finish FROM lot l
        JOIN categories c ON l.id_category = c.id
        LEFT OUTER JOIN rate r ON r.id_lot = l.id
        WHERE date_finish > NOW()
        GROUP BY r.id_lot ORDER BY l.date_creation DESC LIMIT 6";

if ($res = mysqli_query($link, $sql)) {
    $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $content = include_template("content.php", ["categories" => $categories, "products" => $products]);
}
else {
    $content = include_template("404.html", ["error" => $error]);
}

$layout_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $content,
    "user_name" => $user,
    "title" => "Главная страница"
]);

print($layout_content);
?>
