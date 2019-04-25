<?php
require_once("helpers.php");
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect("127.0.0.1", "root", "", "yaticave");/*подключение к СУБД*/
mysqli_set_charset($link, "utf8");

$categories = [];
$products = [];
$content = "";

if ($link == false) {
    $error = mysqli_connect_error();
    $content = include_template("404.html", ["error" => $error]);
}
else {
    $sql = "SELECT name, code FROM categories";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }

    $sql = "SELECT image, lot.name, category.name, price FROM lot JOIN categories ON lot.id_category = categories.id";

    if ($res = mysqli_query($link, $sql)) {
        $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }

    /*Заполняем лот*/
    /*$sql = "SELECT image, name, price, categories.name FROM lot l JOIN categories ON lot.id_category = categories.id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }*/

    $sql = "SELECT image, name, price, categories.name FROM lot l JOIN categories ON lot.id_category = categories.id";
    if ($res = mysqli_query($link, $sql)) {
        $products = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $content = include_template("content.php", ["products" => $products]);
    }
    else {
        $content = include_template("404.html", ["error" => $error]);
    }
}

print_r($products);

$is_auth = rand(0, 1);

$user_name = "Кадиров Сергей"; // укажите здесь ваше имя

$index = 0;
$num_count = count($categories);

/*$categories = [
    [
        "name" => "Доски и лыжи",
        "style" => "boards"
    ],
    [
        "name" => "Крепления",
        "style" => "attachment"
    ],
    [
        "name" => "Ботинки",
        "style" => "boots"
    ],
    [
        "name" => "Одежда",
        "style" => "clothing"
    ],
    [
        "name" => "Инструменты",
        "style" => "tools"
    ],
    [
        "name" => "Разное",
        "style" => "other"
    ]
];*/


/*$products = [
    [
        "name" => "2014 Rossignol District Snowboard",
        "categories" => "Доски и лыжи",
        "price" =>  10999,
        "url" => "img/lot-1.jpg"
    ],
    [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "categories" => "Доски и лыжи",
        "price" => 159999,
        "url" => "img/lot-2.jpg"
    ],
    [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "categories" => "Крепления",
        "price" => 8000,
        "url" => "img/lot-3.jpg"
    ],
    [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "categories" => "Ботинки",
        "price" => 10999,
        "url" => "img/lot-4.jpg"
    ],
    [
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "categories" => "Одежда",
        "price" => 7500,
        "url" => "img/lot-5.jpg"
    ],
    [
        "name" => "Маска Oakley Canopy",
        "categories" => "Разное",
        "price" => 5400,
        "url" => "img/lot-6.jpg"
    ]
];*/ // двумерный массив товаров

// функция для получения цены товара
function get_price ($price) {
    $price_ceil = ceil($price);

    if ($price_ceil > 1000) {
        $price_ceil = number_format($price_ceil, 0, " ", " ");
        $price_ceil = $price_ceil .= " ₽";
        return $price_ceil;
    }
    else {
        return $price_ceil .= " ₽";
    }
}

// функция для форматирования теста изащиты от хакерских атак
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}

// функция для получения времени до окончания показа лота
function get_time_to_end () {
    $now_time = time();
    $mid_time = strtotime("tomorrow");
    $dif_time = $mid_time - $now_time;
    $hours = floor($dif_time / 3600);
    $minutes = floor(($dif_time % 3600) / 60);
    $format_time = $hours.":".$minutes;

    return $format_time;
}

// функция для перевода времени в unixtime
function get_unixtime ($time) {
    $unix_time = strtotime($time);

    return ($time);
}

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
