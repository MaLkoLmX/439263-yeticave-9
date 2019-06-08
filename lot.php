<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

$id = (int)$_GET["id"];
$id_user = $user["id"];

if (isset($_GET["id"])) {
    $sql = "SELECT l.id as id_lot, l.name as title, description, price, step_price, (price + step_price) as min_price, image, c.name as categories, MAX(r.amount) as rate_price, date_finish FROM lot l
        JOIN categories c ON l.id_category = c.id
        LEFT OUTER JOIN rate r ON r.id_lot = l.id
        WHERE l.id = $id
        GROUP BY r.id_lot ORDER BY date_creation DESC LIMIT 6";

    if ($result_lot = mysqli_query($link, $sql)) {
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
        $page_content = include_template("lot.php", ["lots" => $lots]);
    } else {
        $page_content = include_template("error.php", ["error" => $error]);
    }

    $sql = "SELECT count(*) as cnt FROM rate where id_lot = $id";
    $result = mysqli_query($link, $sql);
    $count_rate = mysqli_fetch_assoc($result)['cnt'];

    $sql = "SELECT u.name as user_name, amount, date_rate FROM rate r
            LEFT OUTER JOIN user u ON r.id_user = u.id
            WHERE r.id_lot = $id ORDER BY r.date_rate DESC LIMIT 10";
    $result = mysqli_query($link, $sql);
    $rate = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $form = $_POST;
        $required = ["rate"];
        $errors = [];
        $min_price = $lots[0]["min_price"];

        if (empty($form["rate"])) {
            $errors["rate"] = "Это поле надо заполнить";
        }
        if (empty($errors) && !is_int($form["rate"]) && $form["rate"] <= 0) {
            $errors["rate"] = "Введите целое число больше ноля";
        }
        if (empty($errors) && $form["rate"] < $min_price) {
            $errors["rate"] = "Введите сумму больше минимальной ставки";
        }

        if (!empty($errors)) {
            $page_content = include_template("lot.php", [
                "categories" => $categories,
                "lots" => $lots,
                "errors" => $errors
            ]);
        } else {
            $sql = "INSERT INTO rate (date_rate, amount, id_user, id_lot) VALUES (NOW(), ?, ?, ?)";
            $stmt = db_get_prepare_stmt($link, $sql, [$form["rate"], $id_user, $id]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                header("Refresh: 0");
                die();
            }
        }
    }
}

$page_content = include_template("lot.php", [
    "categories" => get_categories($link),
    "errors" => $errors,
    "lots" => $lots,
    "count_rate" => $count_rate,
    "rate" => $rate
]);

$lots_content = include_template("layout.php", [
    "categories" => get_categories($link),
    "content" => $page_content,
    "user_name" => $user_name,
    "title" => "Лот"
]);

print($lots_content);
?>
