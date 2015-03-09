<?php
header('Content-type: text/html; charset=utf-8');

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

$query  = "SELECT * FROM tbl_order";
$result = mysql_query($query);

if (!$result) die ("Database access failed: " . mysql_error());

$rows = mysql_num_rows($result);

echo "<table><tr> <th>Id</th> <th>№ Заказа</th><th>Дата заказа</th><th>Контакт</th><th>Сумма заказа,у.е.</th></tr>";

for ($j = 0 ; $j < $rows ; ++$j)
{
    $row = mysql_fetch_row($result);
    echo "<tr>";

    for ($k = 0 ; $k < 5 ; ++$k)
        echo "<td>$row[$k]</td>";

    echo "</tr>";
}

echo "</table>";
?>