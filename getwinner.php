<?php
require_once("helpers.php");
require_once("vendor/autoload.php");

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);


$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$id = $user["id"];

if (isset($_SESSION["user"])) {

    $sql = "SELECT r.id_user, u.name as name_user, u.email, l.name as name_lot, l.id FROM lot l
            LEFT OUTER JOIN rate r ON l.id=r.id_lot
            LEFT OUTER JOIN user u ON $id = u.id
            WHERE l.date_finish <= NOW() AND l.id_winner is NULL
            ORDER BY r.date_rate DESC LIMIT 1";

    $result = mysqli_query($link, $sql);
    $rate = mysqli_fetch_assoc($result);
    if ($rate) {
        $sql = "UPDATE lot SET id_winner = (?) WHERE id = (?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$rate[0]["id_user"], $id]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $id_lot = mysqli_insert_id($link);
        }

        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(["keks@phpdemo.ru" => "YetiCave"]);
        $message->setTo($rate["email"]);
        $msg_content = include_template("email.php", [
            "rate" => $rate
        ]);
        $message->setBody($msg_content, "text/html");
        $result = $mailer->send($message);
        if ($result) {
            print("Сообщение успешно отправлено");
        } else {
            print("Не удалось отправить сообщение: " . $logger->dump());
        }
    }
}
?>
