<?php
  require_once("helpers.php");
  date_default_timezone_set("Europe/Moscow");

  $link = mysqli_connect("127.0.0.1", "root", "", "yaticave");/*подключение к СУБД*/
  mysqli_set_charset($link, "utf8");

  $categories = [];
  $lots = [];
  $content = "";

  if (!isset($_GET['id'])) {
    $error = mysqli_connect_error();
    $content = include_template("404.html", ["error" => $error]);
  }
  else {
    $sql = "SELECT name FROM categories";
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

    $sql = "SELECT l.name as title, price, image, c.name as categories, MAX(r.amount), description FROM lot l
            JOIN categories c ON l.id_category = c.id
            JOIN rate r ON r.id_lot = l.id
            WHERE date_finish < NOW()
            GROUP BY r.id_lot ORDER BY date_creation DESC LIMIT 6";

    if ($result = mysqli_query($link, $sql)) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $content = include_template("content.php", ["products" => $products]);
    }
    else {
        $content = include_template("404.html", ["error" => $error]);
    }

    $lots_content = include_template("lot.php", [
        "categories" => $categories,
        "title" => $title,
        "description" => $description,
        "price" => "$price",
        "image" => "$image"
    ]);

    print($lots_content);
?>
