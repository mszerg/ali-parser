<?php
/**
 * Created by PhpStorm.
 * User: mszerg
 * Date: 02.05.16
 * Time: 18:28
 */

//print_r($data);


echo "<table border=\"1\"><tr><td>Наименование</td><td>Содержание</td> </tr>";
echo "<tr><td>id_tovar_order</td><td>$data[id_tovar_order]</td></tr>";
echo "<tr><td>date_order</td><td>" . date('d.m.Y', $data['date_order']). "</td></tr>";
echo "<tr><td>Фотография</td><td><img src=\"/image.php?id=" . $data["id_tovar_order"] . "\" alt=\"\" /></td></tr>";
echo "<tr><td>name</td><td>$data[name]</td></tr>";
echo "<form name='form' action='/tovar2vm/add_rec' method='post'>";
    echo "<tr><td>product_s_desc</td><td><input class='inputtext' type='text' name='product_s_desc' value='$data[product_s_desc]' /></td>
			<tr><td>product_name</td><td><input class='inputtext'type='text' name='product_name' value='$data[product_name]' /></td>
			<tr><td>metadesc</td><td><input class='inputtext' type='text' name='metadesc' value='$data[metadesc]' />
			<tr><td>metakey</td><td><input class='inputtext' type='text' name='metakey' value='$data[metakey]' />
			<tr><td>customtitle</td><td><input class='inputtext' type='text' name='customtitle' value='$data[customtitle]' />
			<tr><td>slug</td><td><input class='inputtext' type='text' name='slug' value='$data[slug]' />
			<tr><td>Добавить запись</td><td><input type='submit' name='addRec' />
			</td></tr>";
echo "</form>";

echo "</table>";

// В примере используется, но не изменяется.
$count = isset($_GET['count']) ? $_GET['count'] : 1;
// Смещение для БД
$start = isset($_GET['start']) ? $_GET['start'] : 0;


    // Получаем объект для работы с БД:
    /*$db = new PDO(
        'mysql:dbname=YOU_DBNAME',
        'USERNAME', 'USERPASS',
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );*/

    //Подключение к базе
    require $_SERVER['DOCUMENT_ROOT'].'/login.php';
    require 'simpagenav.php';

    //echo $db_hostname;
    $db_server = mysql_connect($db_hostname, $db_username, $db_password);

    if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

    mysql_select_db($db_database, $db_server)
    or die("Unable to select database: " . mysql_error());

    // Получаем общее кол-во "страниц"
    //$all = $db_server->query('SELECT COUNT(*) FROM `tbl_order_tovar`')->fetchColumn();
    $result = mysql_query('SELECT * FROM `tbl_order_tovar`');
    $rows = mysql_num_rows($result);

    // Подготавливаем запрос на получение
    // данных теущей "страницы"
    /*$stmt = $db_server->prepare(
        'SELECT *
         FROM `tbl_order_tovar`
         LIMIT  :limit
         OFFSET :offset'
    );

    // Привязываем значения к плейсхолдерам запроса
    $stmt->bindValue(':limit', $count, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $start, PDO::PARAM_INT);

    // Отправляем привязанные данные
    $stmt->execute();
    // Получаем результат запрса:
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // Цикл в котором выводим данные:
    foreach ($pages AS $page) {
        // ... ВЫВОД ДАННЫХ ...
    }*/

    // Выводим блок ссылок с постраничной навигацией:
    $pagenav = new SimPageNav();
    echo $pagenav->getLinks($rows, $count, $start, 10, 'start' );


