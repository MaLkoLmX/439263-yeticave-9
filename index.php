<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");
require_once("getwinner.php");

$sql = "SELECT l.id as id_lot, l.name, price, image, MAX(c.name) as categories, l.date_finish FROM lot l
        JOIN categories c ON l.id_category = c.id
        LEFT OUTER JOIN rate r ON r.id_lot = l.id
        WHERE date_finish > NOW()
        GROUP BY l.id ORDER BY l.date_creation DESC LIMIT 6";

if ($res = mysqli_query($link, $sql)) {
    $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $page_content = include_template("content.php", [
        "categories" => get_categories($link),
        "products" => $products
    ]);
} else {
    $page_content = include_template("404.html", ["error" => $error]);
}

$layout_content = include_template("layout.php", [
    "categories" => get_categories($link),
    "content" => $page_content,
    "user_name" => $user_name,
    "title" => "Главная страница"
]);

print($layout_content);
?>
