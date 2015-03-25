<?php
header('Content-type: text/html; charset=utf-8');
//подгружаем библиотеку
require_once 'library/simple_html_dom.php';


///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");


                $query = "SELECT * FROM tbl_tovar_order";
                echo $query;
                $result = mysql_query($query, $db_server);
                $rows = mysql_num_rows($result);
                echo " - " . $rows;

?>

