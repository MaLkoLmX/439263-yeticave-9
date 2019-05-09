<?php
// функция для получения цены товара
function get_price ($price) {
    $price_ceil = ceil($price);

    if ($price_ceil > 1000) {
        $price_ceil = number_format($price_ceil, 0, " ", " ");
        $price_ceil = $price_ceil .= " ₽";
        return $price_ceil;
    }
    else {
        return $price_ceil .= " ₽";
    }
}

// функция для форматирования ткеста изащиты от хакерских атак
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
}

// функция для получения времени до окончания показа лота
function get_time_to_end () {
    $now_time = time();
    $mid_time = strtotime("tomorrow");
    $dif_time = $mid_time - $now_time;
    $hours = floor($dif_time / 3600);
    $minutes = floor(($dif_time % 3600) / 60);
    $format_time = $hours.":".$minutes;

    return $format_time;
}

// функция для перевода времени в unixtime
function get_unixtime ($time) {
    $unix_time = strtotime($time);

    return ($time);
}
?>
