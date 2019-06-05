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

$sql = "SELECT id, name FROM categories";
$result = mysqli_query($link, $sql);
if (!$link) {
    http_response_code(404);
    $page_content = include_template("error.php", ["categories" => $categories, "error_title" => "Ошибка 404", "error" => "Страницы не найдена"]);
} else {
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $content = include_template("sign-up.php", ["categories" => $categories]);
    } else {
        $error = mysqli_error($link);
        $content = include_template("404.html", ["error" => $error]);
    }

    $lots = [];

    mysqli_query($link, "CREATE FULLTEXT INDEX lot_search ON lot(name, description)");

    $search = $_GET["search"] ?? "";

    if ($search) {

        $cur_page = $_GET["page"] ?? 1;
        $page_items = 6;
        $sql ="SELECT COUNT(*) as cnt FROM lot WHERE MATCH(name, description) AGAINST(?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items_count = mysqli_fetch_assoc($result)["cnt"];
        $pages_count = ceil($items_count / $page_items);
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = "SELECT l.name as name, l.id as id_lot, price, MAX(r.amount) as rate_price, image, MAX(c.name) as category, date_finish FROM lot l
                JOIN categories c ON id_category = c.id
                LEFT JOIN rate r ON r.id_lot = l.id
                WHERE MATCH(l.name, description) AGAINST(?) and date_finish > NOW()
                GROUP BY l.id ORDER BY date_creation LIMIT  $page_items OFFSET $offset";


        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

$page_content = include_template("search.php", [
    "categories" => $categories,
    "lots" => $lots,
    "search" => $search,
    "pages" => $pages,
    "pages_count" => $pages_count,
    "cur_page" => $cur_page
]);

$lots_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "user_name" => $user["name"],
    "title" => "Лот"
]);

print($lots_content);
?>
