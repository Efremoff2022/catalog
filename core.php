<?php

# Настройки сайта
$CORE = require_once($_SERVER['DOCUMENT_ROOT']."/config.php");

# Вывод ошибок
error_reporting(($CORE['DEV_MODE'] ? E_ALL : 0)); // https://www.php.net/manual/ru/function.error-reporting

# Дебаг 
require_once($_SERVER['DOCUMENT_ROOT'] . "/function.php");

# подключение к БД (выполняется один раз)
$CORE['DB'] = mysqli_connect(
    $CORE['DB']['SERVER'],
    $CORE['DB']['USER'],
    $CORE['DB']['PASSWORD'],
    $CORE['DB']['NAME']
);
