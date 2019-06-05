<?php
require_once("helpers.php");
require_once("vendor/autoload.php");

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);


$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$sql_lots_without_winner = "SELECT * FROM lot WHERE id_winner IS NULL AND date_finish <= NOW()";
$res = mysqli_query($link, $sql_lots_without_winner);
$lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
$lots_name = $lots[0]["name"];
$lots_id = $lots[0]["id"];

if (!empty($lots)) {
    foreach ($lots as $lot) {
        $id = $lot["id"] + 1;//не понял почему так получается, что id меньше на один

        $sql = "SELECT r.id, amount, id_user, u.name, u.email FROM rate r
                LEFT OUTER JOIN user u ON r.id_user = u.id
                WHERE r.id_lot = $id
                ORDER BY r.date_rate DESC LIMIT 1";

        $res = mysqli_query($link, $sql);
        $rate = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $user_name = $rate[0]["name"];
        $email = $rate[0]["email"];

        if (!empty($rate)) {

            $sql = "UPDATE lot SET id_winner = ? WHERE id = ?";
            $stmt = db_get_prepare_stmt($link, $sql, [$rate[0]["id_user"], $id]);
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                $result = mysqli_insert_id($link);
            }

            $mailer = new Swift_Mailer($transport);
            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
            $message = new Swift_Message();
            $message->setSubject("Ваша ставка победила");
            $message->setFrom(["keks@phpdemo.ru" => "YetiCave"]);
            $message->setTo([$email]);
            $message_content = include_template("email.php", [
                "rate" => $rate,
                "lots_name" => $lots_name,
                "lots_id" => $lots_id
            ]);

            $message->setBody($message_content, "text/html");
            $result = $mailer->send($message);

            if ($result) {
                print("Сообщение успешно отправлено");
            } else {
                print("Не удалось отправить сообщение: " . $logger->dump());
            }
        }
    }
}
?>
