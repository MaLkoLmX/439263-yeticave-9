<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

$categories = [];
$products = [];
$content = "";

$sql = "SELECT name, code FROM categories";
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($link);
    $content = include_template("404.html", ["error" => $error]);
}

/*Заполняем лот*/
$sql = "SELECT l.name, price, image, c.name as categories, MAX(r.amount) FROM lot l
        JOIN categories c ON l.id_category = c.id
        JOIN rate r ON r.id_lot = l.id
        WHERE date_finish < NOW()
        GROUP BY r.id_lot ORDER BY date_creation DESC LIMIT 6";

if ($res = mysqli_query($link, $sql)) {
    $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $content = include_template("content.php", ["products" => $products]);
}
else {
    $content = include_template("404.html", ["error" => $error]);
}

$is_auth = rand(0, 1);

$user_name = "Кадиров Сергей"; // укажите здесь ваше имя

$index = 0;
$num_count = count($categories);

// подключаем шаблоны
$page_content = include_template("content.php", [
    "categories" => $categories,
    "products" => $products,
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => "Главная страница"
]);

print($layout_content);
?>
