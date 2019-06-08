<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form = $_POST;
    $req_fields = ["email", "password", "name", "message"];
    $errors = [];

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Это поле надо заполнить " . $field;
        }
    }
    if (filter_var($form["email"], FILTER_VALIDATE_EMAIL) === false) {
        $errors["email"] = "Email введен неверно";
    }
    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $form["email"]);
        $sql = "SELECT id FROM user WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors[] = "Пользователь с этим email уже зарегистрирован";
            $content = include_template("sign-up.php",
                ["categories" => $categories, "errors" => $errors, "form" => $form]);
        } else {
            $password = password_hash($form["password"], PASSWORD_DEFAULT);

            $sql = "INSERT INTO user(date_reg, email, name, password) VALUES(NOW(), ?, ?, ?)";
            $stmt = db_get_prepare_stmt($link, $sql, [$form["email"], $form["name"], $password]);
            $res = mysqli_stmt_execute($stmt);
        }
        if ($res && empty($errors)) {
            header("Location:/login.php");
            die();
        }
    }
}

$page_content = include_template("sign-up.php", [
    "categories" => get_categories($link),
    "errors" => $errors,
    "form" => $form
]);

$sign_up_content = include_template("layout.php", [
    "categories" => get_categories($link),
    "content" => $page_content,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => "Регистрация"
]);

print($sign_up_content);
?>
