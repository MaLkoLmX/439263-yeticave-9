<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

$categories = [];
$content = "";

$sql = "SELECT id, name FROM categories";
$result = mysqli_query($link, $sql);
if (!$link) {
    header("Location: /404.php");
    die();
} else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $page_content = include_template("sign-up.php", ["categories" => $categories]);
    } else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form = $_POST;
    $req_fields = ["email", "password", "name", "message"];
    $errors = [];

    foreach ($req_fields as $field) {
      if (empty($form[$field])) {
          $errors[$field] = "Это поле надо заполнить " . $field;
          $page_content = include_template("sign-up.php", ["categories" => $categories, "errors" => $errors, "form" => $form]);
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
            $page_content = include_template("sign-up.php", ["categories" => $categories, "errors" => $errors, "form" => $form]);
        } else {
          $password = password_hash($form["password"], PASSWORD_DEFAULT);

          $sql = 'INSERT INTO user (date_reg, email, name, password) VALUES (NOW(), ?, ?, ?)';
          $stmt = db_get_prepare_stmt($link, $sql, [$form["email"], $form["name"], $password]);
          $res = mysqli_stmt_execute($stmt);
        }
        if ($res && empty($errors)) {
            header("Location: /login.php");
            die();
        }
    }
}

$sign_up_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "title" => "Регистрация"
]);

print($sign_up_content);
?>
