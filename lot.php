<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

session_start();

if ($_SESSION) {
    $user = $_SESSION["user"];
}

$categories = [];
$lots = [];
$content = "";

$id = (int) $_GET['id'];
$sql = "SELECT name FROM categories";
$result = mysqli_query($link, $sql);

if (!isset($_GET['id'])) {//проверяем на наличие ID
    http_response_code(404);
    $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 404", "error" => "Страницы не найдена"]);
} else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }
}

$sql = "SELECT l.id as id_lot, l.name as title, description,  price, step_price, image, c.name as categories, MAX(r.amount), date_finish FROM lot l
    JOIN categories c ON l.id_category = c.id
    JOIN rate r ON r.id_lot = l.id
    WHERE l.id = $id
    GROUP BY r.id_lot ORDER BY date_creation DESC LIMIT 6";

if ($result_lot = mysqli_query($link, $sql)) {
    $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
    $content = include_template("lot.php", ["lots" => $lots]);
} else {
    $content = include_template("404.html", ["error" => $error]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ["rate"];
    $errors = [];

    if (empty($form["rate"])) {
        $errors[$key] = "Эти поля надо заполнить " . $key;
        $content = include_template("lot.php", ["categories" => $categories, "errors" => $errors, "form" => $form]);
    }

    if (!empty($errors) && !is_int($form["rate"]) && $form["rate"] <= 0) {
        $errors["rate"] = "Введите целое число больше ноля";
    }

    if (count($errors)) {
        $page_content = include_template("lot.php", [
            "categories" => $categories,
            "lots" => $lots,
            "errors" => $errors,
        ]);
    } else {
        $sql = "INSERT INTO rate (date_rate, amount, id_user, id_lot) VALUES (NOW(), ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$form['rate'], $user['id'], $id]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) { //елси верно - обновляем страницу
            header("Refresh: 0");
            die();
        } else {
            $page_content = include_template("error.php", ["error" => mysqli_error($link)]);
        }
    }
}

$page_content = include_template("lot.php", [
    "categories" => $categories,
    "lots" => $lots
]);

$lots_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "user_name" => $user["name"],
    "title" => "Лот"
]);

print($lots_content);
?>
