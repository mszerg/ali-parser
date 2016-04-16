<?php
//header('Content-type: text/html; charset=utf-8');

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");
////////////////////////////////////// Отрабатывваем кнопку Об-ть кол-во
if (isset($_POST['u_count']) && isset($_POST['id_tovar_order']))
{
    $id_tovar_order = get_post('id_tovar_order');
    $u_count = get_post('u_count');
    $query = "UPDATE tbl_order_tovar SET count='$u_count' WHERE id_tovar_order='$id_tovar_order'";
    //echo $query;
    if (!mysql_query($query, $db_server))
        echo "Update failed: $query<br>" .
            mysql_error() . "<br><br>";
}

else echo "Ошибка";



function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
}

?>