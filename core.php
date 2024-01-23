<?php

# Настройки сайта
$CORE = require_once($_SERVER['DOCUMENT_ROOT']."/config.php");

# Дебаг 
require_once($_SERVER['DOCUMENT_ROOT'] . "/function.php");

# подключение к БД (выполняется один раз)
$CORE['DB'] = mysqli_connect(
    $CORE['DB']['SERVER'],
    $CORE['DB']['USER'],
    $CORE['DB']['PASSWORD'],
    $CORE['DB']['NAME']
);
// dd($CORE);