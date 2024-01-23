<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");





# Подготовка данных для формы
$categ = mysqli_query($CORE['DB'], "SELECT * FROM `category`");
$colorsAuto = mysqli_query($CORE['DB'], "SELECT * FROM `color`");
$tagsAuto = mysqli_query($CORE['DB'], "SELECT * FROM `tag`");

while($row_colorsAuto = mysqli_fetch_assoc($colorsAuto)) {
    $arColorsAuto[$row_colorsAuto['id']] = $row_colorsAuto;
}

while($row_tagsAuto = mysqli_fetch_assoc($tagsAuto)) {
    $arTagsAuto[$row_tagsAuto['id']] = $row_tagsAuto;
}

while($row_category = mysqli_fetch_assoc($categ)) {
    $arRowCategory[$row_category['id']] = $row_category;
}


# Обработка отправки формы (создание автомобиля)
if(!empty($_POST)) {

    // Проверка входных данных
    if(empty($_POST['category_id'])) die("Выберите категорию <a href=\"/create.php\">К форме</a>");
    if(empty($_POST['description'])) die("Напишите описание <a href=\"/create.php\">К форме</a>");
    if(empty($_POST['colors'])) die("Выберите цвет <a href=\"/create.php\">К форме</a>");
    if(empty($_POST['tags'])) die("Выберите комплектацию <a href=\"/create.php\">К форме</a>");
    if(empty($_FILES)) die("Загрузите фото автомобиля <a href=\"/create.php\">К форме</a>");

    // Создание автомобиля
    mysqli_query($CORE['DB'], "INSERT INTO `product`(`name`, `description`, `category_id`) VALUES(
        '".$_POST['name']."',
        '".$_POST['description']."',
        '".$_POST['category_id']."'
    )");
    $lastProductId = mysqli_insert_id($CORE['DB']); // только что созданный новый авто (в product)

    // Связи цветов с только что созданным автомобилем
    foreach($_POST['colors'] as $colorProduct_id) {
        mysqli_query($CORE['DB'], "INSERT INTO `product_color_mtm` VALUES (
            0,
            ".$lastProductId.",
            ".$colorProduct_id."
        )");
    }

    // Связи тегов с только что созданным автомобилем
    foreach($_POST['tags'] as $tagsProduct_id) {
        mysqli_query($CORE['DB'], "INSERT INTO `product_tag_mtm` VALUES (
            0,
            ".$lastProductId.",
            ".$tagsProduct_id."
        )");
    }

    // Загрузка фото автомобиля
    move_uploaded_file($_FILES['picture']['tmp_name'], "static/images/".$_FILES['picture']['name']);

    // Редирект (перенаправление) на главную
    header("location: /");
}
/*
TODO: Сделать проверку загружаемого фото
https://snipp.ru/php/uploads-files?ysclid=lqeyxsjrqo324704976
*/
?>
<a href="/">К списку автомобилей</a>
<form action="" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="title">Заголовок</label></td>
            <td><input type="text" id="title" name="name" required></td>
        </tr>
        <tr>
            <td><label for="description">Описание</label></td>
            <td><textarea id="description" name="description"></textarea></td>
        </tr>
        <tr>
            <td><label for="category_id">Категории</label></td>
            <td>
                <select id="category_id" name="category_id">
                    <option value=""></option>
                    <?php foreach($arRowCategory as $cat) { ?>
                        <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="color">Цвета</label></td>
            <td>
                <select id="color" name="colors[]" size="5" multiple>
                    <?php foreach($arColorsAuto as $colAuto) { ?>
                        <option value="<?=$colAuto['id']?>"><?=$colAuto['name']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="tag">Комплектация</label></td>
            <td>
                <select id="tag" name="tags[]" size="5" multiple>
                    <?php foreach($arTagsAuto as $tagAuto) { ?>
                        <option value="<?=$tagAuto['id']?>"><?=$tagAuto['name']?></option>
                    <?php } ?>
                </select> 
            </td>
        </tr>
        <tr>
            <td><label for="picture">Загрузить фото автомобиля</label></td>
            <td><input type="file" name="picture" id="picture"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Создать новый автомобиль"></td>
        </tr>
        
    </table>   
</form>












