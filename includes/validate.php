<?php
// функция очистки данных от тегов
function clean($array) {
    foreach ($array as $key => $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        $array[$key] = $value;
    }
    return $array;
}


// Проверка длинны
function check_length($value, $min, $max) {
    $length = strlen($value);
    if ($length >= $min AND $length <= $max) {
        $result = TRUE;
    } else $result = FALSE;
    return $result;
}