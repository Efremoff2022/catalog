<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

# выполняем SQL-запрос на извлечение данных
$categoryQuery = mysqli_query($CORE['DB'], "SELECT * FROM `category`");
$tagQuery = mysqli_query($CORE['DB'], "SELECT * FROM `tag`");
$productQuery = mysqli_query($CORE['DB'], "SELECT * FROM `product`");
$productTagQuery = mysqli_query($CORE['DB'], "SELECT * FROM `product_tag_mtm`");

$colorQuery = mysqli_query($CORE['DB'], "SELECT * FROM `color`");
$pruductColorQuery = mysqli_query($CORE['DB'], "SELECT * FROM `product_color_mtm`");

# обрабатываем полученные данные в массив

// Категории
while($row = mysqli_fetch_assoc($categoryQuery)) {
    $arCategory[$row['id']] = $row;
}

// Теги
while($row = mysqli_fetch_assoc($tagQuery)) {
    $arTag[$row['id']] = $row;
}

// Теги товаров (mtm)
while($row = mysqli_fetch_assoc($productTagQuery)) {
    $productTag_mtm[$row['id']] = $row;
}

// Цвета
while($row = mysqli_fetch_assoc($colorQuery)) {    
    $arColor[$row['id']] = $row;
}

// Теги цветов
while($row_productColor_mtm = mysqli_fetch_assoc($pruductColorQuery)) {    
    $arProdColor_mtm[$row_productColor_mtm['id']] = $row_productColor_mtm;
}

// Товары
while($row = mysqli_fetch_assoc($productQuery)) {
    // Список цветов
    $row['colors'] = [];
    foreach($arProdColor_mtm as $pc) {
        if($pc['product_id'] == $row['id']) { 
            $row['colors'][] = $arColor[$pc['color_id']]['name'];
        }
    }

    // Список тегов
    $row['tags'] = [];
    foreach($productTag_mtm as $pt) { // Обходим весь список тегов
        if($pt['product_id'] == $row['id']) { // Отсеиваем теги, которые не относятся к конкретному товару (т.е. ищем совпадения тегов из общего списка)
            $row['tags'][] = $arTag[$pt['tag_id']]['name']; // формируем готовые теги конкретного товара
        }
    }    

    // Название категории
    $row['category'] = $arCategory[$row['category_id']]['name'];

    // Преобразование даты
    $row['created_at'] = ruDate($row['created_at']);

    // Результат
    $arProducts[$row['id']] = $row;
}


/*
$arColor = [
    "Красный",
    "Синий",
    "Жёлтый",
    "Чёрный",
    "Белый",
];
*/

/*
$arCategory = [
    1 => [
        "id" => 1,
        "name" => "Легковые",
        "created_at" => "2023-11-19 14:00:00",
    ],

    2 => [
        "id" => 2,
        "name" => "Кроссовер",
        "created_at" => "2023-09-12 14:00:00",
    ],

    3 => [
        "id" => 3,
        "name" => "Внедорожник",
        "created_at" => "2023-11-10 14:00:00",
    ]
];
*/

/*
$arTag = [
    1 => [
        "id" => 1,
        "name" => "АКПП",
        "created_at" => "",
    ],

    2 => [
        "id" => 2,
        "name" => "Седан",
        "created_at" => "",
    ],

    3 => [
        "id" => 3,
        "name" => "Люк",
        "created_at" => "",
    ],

    4 => [
        "id" => 4,
        "name" => "Без люка",
        "created_at" => "",
    ],

    5 => [
        "id" => 5,
        "name" => "Сигнализация",
        "created_at" => "",
    ]
];
*/

/*
$arProducts = [
    1 => [
        "id" => 1,
        "name" => "Audi",
        "description" => "Автомобиль с кожаным салоном",
        "color" => [0, 2],
        "created_at" => "2023-08-12 14:00:00",
        "category_id" => 3,
        "tags_id" => [1, 3],
    ],

    2 => [
        "id" => 2,
        "name" => "Volvo",
        "description" => "Семейный автомобиль с хорошей управляемостью",
        "color" => [0, 3, 4],
        "created_at" => "2023-05-16 18:00:00",
        "category_id" => 2,
        "tags_id" => [2, 3, 5],
    ],

    3 => [
        "id" => 3,
        "name" => "BMW",
        "description" => "Быстрый автомобиль для города и трассы",
        "color" => [1, 3],
        "created_at" => "2023-10-14 15:00:00",
        "category_id" => 1,
        "tags_id" => [1, 4],
    ]
];
*/

// function lineInTag($productID, $productName, $strong)
// {
//     if ($productID % 2 == 0) {
//         echo $strong.$productName;
//     }
//     else {
//         echo $productName;
//     }
// }

// test_1
// test_2
// test_3



?>

<a href="/create.php">Создать новый автомобиль</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Категория</th>
        <th>Марка авто</th>
        <th>Описание</th>
        <th>Цвета</th>
        <th>Комплектация</th>
        <th>Создано</th>
        <th>Фото</th>
        <th></th>
    </tr>
    <?php 
    $iterCount = 0; // подсчет итераций
    foreach ($arProducts as $key => $product) { 
        $iterCount++;
    ?>
    <tr>
        <td><?=$product['id']?></td>
        <td><?=$product['category']?></td>
        <td><?php
            if($iterCount % 2 == 0) {
                echo lineInTag($product['name'], "strong");
            }
            else {
                echo lineInTag($product['name'], "em");
            }
            ?>
        </td>
        <td><?=$product['description']?></td>
        <td><?=implode(', ', $product['colors'])?></td>
        <td><?=implode(', ', $product['tags'])?></td>
        <td><?=$product['created_at']?></td>
        <td><img src="/static/images/<?=$product['id'] ?>.jpg" style="width: 150px; height: auto"></td>
        <td>
            <form action="/delete.php" method="post">
                <input type="hidden" name="product_id" value="<?=$product['id']?>">
                <input type="submit" value="Удалить">
            </form>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="4"><?="Кол-во записей в массиве: " . count($arProducts)?></td>
        <td colspan="4"><?="Кол-во записей в первом вложенном массиве: " . count($arProducts[1])?></td>
    </tr>
</table>














