<?php
  require_once("helpers.php");
  require_once("functions.php");
  require_once("link.php");

  $categories = [];
  $lots = [];
  $content = "";


  if (!isset($_GET['id'])) {//проверяем на наличие ID
    header("Location: /404.php");
  }
  else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }

    $id = $_GET['id'];
    $sql = "SELECT name FROM categories";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }

    $sql = "SELECT l.name as title, price, image, c.name as categories, description, price + step_price as min_price FROM lot l
            JOIN categories c ON l.id_category = c.id
            WHERE l.id = $id;"

    if ($result_lot = mysqli_query($link, $sql)) {
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
        $content = include_template("lot.php", ["lots" => $lots]);
    }
    else {
        $content = include_template("404.html", ["error" => $error]);
    }

    $lots_content = include_template("layout.php", [
        "categories" => $categories,
        "content" => $content,
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "title" => "Лот"
    ]);

    print($lots_content);
?>
