<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

$categories = [];
$lots = [];
$content = "";

$sql = "SELECT name FROM categories";
$result = mysqli_query($link, $sql);
if (!$link) {
    header("Location: /404.php");
} else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $page_content = include_template("add-lot.php", ["categories" => $categories]);
    } else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lot = $_POST["lot"];
    $required = ["lot-name", "message", "lot-rate", "lot-step", "lot-date"];
    $dict = ["lot-name" => "Название", "message" => "Описание товара", "lot-rate" => "Стартовая цена", "lot-step" => "Ставка", "lot-date" => "Дата окончания лота", "lot_image" => "Фото тоавра", "lot-cat" => "Категория товара"];
    $errors = [];

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = "Это поле надо заполнить";
        }
    }
    if ($_POST["lot-cat"] == "Выберите категорию") {
        $errors["lot-cat"] = "Категория не выбрана";
    }
    if (!is_date_valid($_POST["lot-date"])) {
        $errors["lot-date"] = "Неправильный формат даты";
    }
    if(!is_int($_POST["lot-rate"]) || empty($_POST["lot-rate"])) {
        $errors["lot-rate"] = "Введите число";
    }
    if(!is_int($_POST["lot-step"]) || empty($_POST["lot-step"])) {
        $errors["lot-step"] = "Введите число!";
    }

    if (isset($_FILES["lot_image"]["name"])) {
        $tmp_name = $_FILES["lot_image"]["tmp_name"];
        $path = $_FILES["lot_image"]["name"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
            $errors["file"] = "Загрузите картинку в другом формате";
        }   else {
            move_uploaded_file($tmp_name, "uploads/" . $path);
            $lot["path"] = "uploads/" . $path;
        }
    } else {
        $errors["file"] = "Вы не загрузили файл";
    }

    if (count($errors)) {
        $page_content = include_template("add-lot.php", [
            "categories" => $categories,
            "lot" => $lot,
            "errors" => $errors,
            "dict" => $dict
        ]);
    } else {
        $sql = "INSERT INTO lots (title, description, price, date_finish, step_price, id_category, image, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = db_get_prepare_stmt($link, $sql, [$lot["title"], $lot["description"], $lot["price"], $lot["date_finish"], $lot["step_price"], $lot["id_category"], $lot["image"]]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            $content = include_template("error.php", ["error" => mysqli_error($link)]);
        }
    }
} else {
    $error = mysqli_error($link);
    $content = include_template("404.html", ["error" => $error]);
}


$add_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => "Добавить лот"
]);

print($add_content);
?>
