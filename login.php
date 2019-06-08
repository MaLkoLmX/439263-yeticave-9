<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form = $_POST;

    $required = ["email", "password"];
    $errors = [];

    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Это поле надо заполнить";
        }
    }

    if (isset($form["email"])) {
        $email = mysqli_real_escape_string($link, $form["email"]);
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
    }

    if (!count($errors) && $user) {
        if (password_verify($form["password"], $user["password"])) {
            $_SESSION["user"] = $user;
        } else {
            $errors["password"] = "Неверный пароль";
        }
    } else {
        $errors["email"] = "Такой пользователь не найден";
    }

    if (count($errors)) {
        $page_content = include_template("login.php", [
            "form" => $form,
            "errors" => $errors,
            "categories" => get_categories($link)
        ]);
    } else {
        header("Location: /index.php");
        die();
    }
} else {
    if (isset($_SESSION["user"])) {
        header("Location: /index.php");
        die();
    } else {
        $page_content = include_template("login.php", [
            "categories" => get_categories($link)
        ]);
    }
}

$login_content = include_template("layout.php", [
    "categories" => get_categories($link),
    "content" => $page_content,
    "user_name" => $user_name,
    "title" => "Войти"
]);

print($login_content);
?>
