<?php
/**
 * Округляет число и добовляет знак ₽
 *
 * @param $price число
 *
 * @return цена
 */
function get_price($price)
{
    $price_ceil = ceil($price);

    if ($price_ceil > 1000) {
        $price_ceil = number_format($price_ceil, 0, " ", " ");
        $price_ceil = $price_ceil .= " ₽";
        return $price_ceil;
    } else {
        return $price_ceil .= " ₽";
    }
}

/**
 * Удаляет теги из текста
 *
 * @param $str текст
 *
 * @return $text текст без тегов
 */
function esc($str)
{
    $text = htmlspecialchars($str);

    return $text;
}

/**
 * Переводит время в секунды
 *
 * @param $time время из бд
 *
 * @return $unix_time время в секундах
 */
function get_unixtime($time)
{
    $unix_time = strtotime($time);

    return $unix_time;
}

/**
 * Получает время в нужном формате
 *
 * @param $time время полученное из бд
 *
 * @return $format_time время в формате h:m
 */
function get_time(string $time)
{
    $date_end = strtotime($time);
    $secs_to_end = $date_end - time();
    $hours = floor($secs_to_end / 3600);
    $minutes = floor(($secs_to_end % 3600) / 60);

    return $hours . " : " . $minutes;
}

/**
 * Получает время начала ставки и возвращает корректную форму множественного числа
 *
 * @param $date время полученное из бд
 *
 * @return $date время в нужном формате
 */
function date_bets($date)
{
    $date = strtotime($date);
    $time = time() + 3600;
    $date_diff = $time - $date;
    $minutes = floor(($date_diff % 3600) / 60);
    $hours = floor($date_diff / 3600);

    if ($date_diff < 60) {
        $date = "только что";
    } elseif ($date_diff < 3600 && $date_diff >= 60) {
        $date = $minutes . " " . get_noun_plural_form($minutes, "минута", "минуты", "минут") . " назад";
    } elseif ($date_diff < 7200 && $date_diff >= 3600) {
        $date = "час назад";
    } elseif ($date_diff < 86400 && $date_diff >= 7200) {
        $date = $hours . " " . get_noun_plural_form($hours, "час", "часа", "часов") . " назад";
    } elseif ($date_diff < 172800 && $date_diff >= 86400) {
        $date = "Вчера, в " . date("H:i", $date);
    } else {
        $date = date("d.m.y", $date) . " в " . date("H:i", $date);
    }

    return $date;
}
?>
