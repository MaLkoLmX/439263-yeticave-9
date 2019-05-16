<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

session_start();

$categories = [];
$lots = [];
$content = "";

$sql = "SELECT id, name FROM categories";
$result = mysqli_query($link, $sql);
if (!$link) {
    header("Location: /404.php");
    die();
} else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $page_content = include_template("add-lot.php", ["categories" => $categories]);
    } else {
        http_response_code(404);
        $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 404", "error" => "Страницы не найдена"]);
    }
}

if ($_SESSION) {
    $user = $_SESSION["user"]["name"];
}

if (isset($_SESSION["user"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lots = $_POST;
        $required = ["lot-name", "message", "lot-rate", "lot-step", "lot-date, lot-cat"];
        $dict = ["lot-name" => "Название", "message" => "Описание товара", "lot-rate" => "Стартовая цена", "lot-step" => "Ставка", "lot-date" => "Дата окончания лота", "lot_image" => "Фото тоавра", "lot-cat" => "Категория товара"];
        $errors = [];

        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = "Эти поля надо заполнить " . $key;
            }
        }
        if (empty($errors) && $_POST["lot-cat"] == "Выберите категорию") {
            $errors["lot-cat"] = "Категория не выбрана";
        }
        if (empty($errors) && !is_date_valid($_POST["lot-date"])) {
            $errors["lot-date"] = "Неправильный формат даты";
        }
        if (empty($errors) && !strtotime($_POST["lot-date"]) < (strtotime("today") + 86400)) {
            $errors["lot-date"] = "Укажите дату окончания не раньше, чем через 24 часа";
        }
        if (!empty($errors) && !is_int($_POST["lot-rate"]) && $_POST["lot-rate"] <= 0) {
            $errors["lot-rate"] = "Введите целое число больше ноля";
        }
        if (!empty($errors) && !is_int($_POST["lot-step"]) && $_POST["lot-step"] <= 0) {
            $errors["lot-step"] = "Введите целое число больше ноля";
        }

        if ($_FILES["image"]["error"] = 0) {
            $tmp_name = $_FILES["image"]["tmp_name"];
            $path = uniqid() . $_FILES["image"]["name"];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
                $errors["image"] = "Загрузите картинку в другом формате";
            }   else {
                move_uploaded_file($tmp_name, "uploads/" . $path);
                $lots["image"] = "uploads/" . $path;
            }
        } else {
            $errors["image"] = "Вы не загрузили файл";
        }

        if (count($errors)) {
            $page_content = include_template("add-lot.php", [
                "categories" => $categories,
                "lot" => $lots,
                "errors" => $errors,
                "dict" => $dict
            ]);
        } else {
            $sql = "INSERT INTO lot (title, description, price, date_finish, step_price, id_category, image, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = db_get_prepare_stmt($link, $sql, [$lots["title"], $lots["description"], $lots["price"], $lots["date_finish"], $lots["step_price"], $lots["id_category"], $lots["image"]]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?id=" . $lot_id);
                die();
            } else {
                $content = include_template("error.php", ["error" => mysqli_error($link)]);
            }
        }
    } else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }
} else {
    http_response_code(403);
    $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 403", "error" => "Пожалуйста авторизуйтесь"]);
}


$add_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "user_name" => $user,
    "title" => "Добавить лот"
]);

print($add_content);
?>
