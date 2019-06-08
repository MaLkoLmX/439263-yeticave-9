<?php
require_once("helpers.php");
require_once("functions.php");
require_once("link.php");

mysqli_query($link, "CREATE FULLTEXT INDEX lot_search ON lot(name, description)");

$cat_id = $_GET["cat"] ?? "";
$cat_name = $_GET["name"] ?? "";

if (!empty($cat_id)) {

    $cur_page = $_GET["page"] ?? 1;
    $page_items = 6;
    $sql = "SELECT COUNT(*) as cnt FROM lot where id_category = (?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$cat_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)["cnt"];
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = "SELECT l.name as name, l.id as id_lot, price, MAX(r.amount) as rate_price, image, MAX(c.name) as category, date_finish FROM lot l
                JOIN categories c ON id_category = c.id
                LEFT JOIN rate r ON r.id_lot = l.id
                WHERE id_category = (?) and date_finish > NOW()
                GROUP BY l.id ORDER BY date_creation LIMIT  $page_items OFFSET $offset";

    $stmt = db_get_prepare_stmt($link, $sql, [$cat_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


$page_content = include_template("all-lots.php", [
    "categories" => get_categories($link),
    "lots" => $lots,
    "cat_id" => $cat_id,
    "cat_name" => $cat_name,
    "pages" => $pages,
    "pages_count" => $pages_count,
    "cur_page" => $cur_page
]);

$layout_content = include_template("layout.php", [
    "content" => $page_content,
    "categories" => get_categories($link),
    "user_name" => $user_name,
    "title" => "Все лоты"
]);
print($layout_content);
?>
