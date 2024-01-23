<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");
// dd($_POST);
$del = mysqli_query($CORE['DB'], "DELETE FROM `product` WHERE `id`=".$_POST['product_id']);

// TODO: Удалить все mtm связи


// Редирект (перенаправление) на главную
header("location: /");