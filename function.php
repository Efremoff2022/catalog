<?php
/*
Дебаг - вывод значения в браузер.
Dump — выдача информации о состоянии системы или её части. 
require_once($_SERVER['DOCUMENT_ROOT']."/var_dumper.php");
*/
function dump($data) {
    echo "<pre style=\"margin: 10px 0; padding: 10px; background: #ececec; white-space: pre-wrap;\">";
    print_r($data);
    echo "</pre>";
}

// dump and die - вывести и остановить
function dd($data) {
    die(dump($data));
}

# Заворачивает строку в парный тег
function lineInTag($line, $tag) {
    return "<$tag>".$line."</$tag>";
}

# Преобразование даты с месяцем на русском
function ruDate($dateTime) {
    $arMonth = [
        1 => "Январь",
        2 => "Февраль",
        3 => "Март",
        4 => "Апрель",
        5 => "Май",
        6 => "Июнь",
        7 => "Июль",
        8 => "Август",
        9 => "Сентябрь",
        10 => "Октябрь",
        11 => "Ноябрь",
        12 => "Декабрь",
    ];

    

    $arDate = explode(" ", $dateTime); // принимает "2023-11-14 15:03:22", и дробит по пробелу, в индексе 0 будет "2023-11-14", в индексе 1 будет "15:00:00"
    $date = explode("-", $arDate[0]); // 0 - "2023", 1 - "11", 2 - "14"
    $time = explode(":", $arDate[1]); // 0 - "15", 1 - "03", 2 - "22"

    // if(str_contains($date[1], "0")) {
    if(!substr($date[1], 0, 1)) {
        $date[1] = str_replace("0", "", $date[1]);
    }
    
    return $date[2]." ".mb_strtolower($arMonth[$date[1]])." ".$date[0]." "."(".$time[0].":".$time[1].")";
}

