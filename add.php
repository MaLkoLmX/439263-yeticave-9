<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lots = $_POST;
    $required = ["name", "description", "price", "step_price", "date_finish", "category"];
    $dict = [
        "name" => "Название",
        "description" => "Описание товара",
        "price" => "Стартовая цена",
        "step_price" => "Ставка",
        "date_finish" => "Дата окончания лота",
        "lot_image" => "Фото тоавра",
        "category" => "Категория товара"
    ];

    foreach ($required as $key) {
        if (empty($lots[$key])) {
            $errors[$key] = "Эти поля надо заполнить " . $key;
        }
    }
    if (empty($errors) && $lots["category"] > 6 || $lots["category"] < 1) {
        $errors["category"] = "Категория не выбрана";
    }
    if (empty($errors) && !is_date_valid($lots["date_finish"])) {
        $errors["date_finish"] = "Неправильный формат даты";
    }
    if (empty($errors) && strtotime($lots["date_finish"]) < (strtotime("today") + 86400)) {
        $errors["date_finish"] = "Укажите дату окончания не раньше, чем через 24 часа";
    }
    if (!empty($errors) && !is_int($lots["price"]) && $lots["price"] <= 0) {
        $errors["price"] = "Введите целое число больше ноля";
    }
    if (!empty($errors) && !is_int($lots["step_price"]) && $lots["step_price"] <= 0) {
        $errors["step_price"] = "Введите целое число больше ноля";
    }

    if ($_FILES["image"]["error"] == 0) {
        $tmp_name = $_FILES["image"]["tmp_name"];
        $path = uniqid() . $_FILES["image"]["name"];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
            $errors["image"] = "Загрузите картинку в другом формате";
        } else {
            move_uploaded_file($tmp_name, "uploads/" . $path);
            $lots["image"] = "uploads/" . $path;
        }
    } else {
        $errors["image"] = "Вы не загрузили файл";
    }

    if (!empty($errors)) {
        $page_content = include_template("add-lot.php", [
            "categories" => get_categories($link),
            "lots" => $lots,
            "errors" => $errors,
            "dict" => $dict
        ]);
    } else {
        $lots["id_user"] = $user["id"];
        $sql = "INSERT INTO lot (date_creation, name, description, image, price, date_finish, step_price, id_user, id_category) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [
            $lots["name"],
            $lots["description"],
            $lots["image"],
            $lots["price"],
            $lots["date_finish"],
            $lots["step_price"],
            $lots["id_user"],
            $lots["category"]
        ]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
            die();
        }
    }
} else {
    $page_content = include_template("add-lot.php", [
        "categories" => get_categories($link)
    ]);
}

if (!isset($_SESSION["user"])) {
    $page_content = include_template("error.php", [
        "categories" => get_categories($link),
        "error_title" => "Ошибка 403",
        "error" => "Пожалуйста авторизуйтесь"
    ]);
}

$add_content = include_template("layout.php", [
    "categories" => get_categories($link),
    "content" => $page_content,
    "user_name" => $user_name,
    "title" => "Добавить лот"
]);

print($add_content);
?>
