<?php
if ( isset( $_GET['id'] ) ) {
    // Здесь $id номер изображения
    $id = (int)$_GET['id'];
    if ( $id > 0 ) {
        require_once 'login.php';
        $db_server = mysql_connect($db_hostname, $db_username, $db_password);
        if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
        mysql_select_db($db_database, $db_server)
        or die("Unable to select database: " . mysql_error());
        mysql_query("SET NAMES utf8");

        $query = "SELECT `picture` FROM `tbl_tovar_order` WHERE `id_tovar_order`=".$id;
        // Выполняем запрос и получаем файл
        $res = mysql_query($query, $db_server);
        if ( mysql_num_rows( $res ) == 1 ) {
            $image = mysql_fetch_array($res);
            // Отсылаем браузеру заголовок, сообщающий о том, что сейчас будет передаваться файл изображения
            //header("Content-type: image/*");
            header("Content-Type: image/jpg");  //указываем браузеру что это изображение
            // И  передаем сам файл
            echo $image['picture'];
        }
    }
}
?>