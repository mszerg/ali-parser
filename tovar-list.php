<?php

?>
<form name="form" action="" method="post">
    <table>
        <tr>
            <td>Заказ   <input type="text" name="find_order"  /></td>
            <td>Нач дата<input type="text" name="begin_date" /></td>
            <td>Кон дата<input type="text" name="end_date" /></td>
            <td>Товар   <input type="text" name="find_tovar" /></td>
            <td colspan="2">
                <input type="submit" name="filter" value="Фильтр" />
            </td>
        </tr>
    </table>
</form>

<?php

if (!empty($_POST["filter"])) {
	SetCookie("filtr_tovar",$_POST["find_tovar"],time()+60*60*24,'/');
	//SetCookie("Test","Value");
	if (SetCookie("Test","Value")) echo "<h3>Cookies успешно установлены!</h3>";
	echo "куки записан";
}

header('Content-type: text/html; charset=utf-8');

function addWhere($where, $add, $and = true) {
    if ($where) {
        if ($and) $where .= " AND $add";
        else $where .= " OR $add";
    }
    else $where = $add;
    return $where;
}
if (!empty($_POST["filter"])) {
    $where = "";
    if ($_POST["begin_date"]) $where = addWhere($where, "`date_order` >= '".htmlspecialchars($_POST["begin_date"]))."'";
    if ($_POST["end_date"]) $where = addWhere($where, "`date_order` <= '".htmlspecialchars($_POST["end_date"]))."'";
    //if ($_POST["manufacturers"]) $where = addWhere($where, "`manufacturer` IN (".htmlspecialchars(implode(",", $_POST["manufacturers"])).")");
    //if ($_POST["find_order"]) $where = addWhere($where, "`wifi` = '1'");
    if ($_POST["find_order"]) $where = addWhere($where, "`namber_order` = '" . htmlspecialchars($_POST["find_order"])) . "'";
    if ($_POST["find_tovar"]) $where = addWhere($where, "`name` like '%" . htmlspecialchars($_POST["find_tovar"])) . "%'";
    $sql  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* , `tbl_order`.`status` FROM tbl_tovar_order INNER JOIN `tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`";
    if ($where) $sql .= " WHERE $where";
    $sql .= " ORDER BY `namber_order` DESC";
	echo "1";
}
else
{
	$sql  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* , `tbl_order`.`status` FROM tbl_tovar_order INNER JOIN `tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`  ORDER BY `namber_order` DESC";
	echo "2";
}
?>

<?php
//header('Content-type: text/html; charset=utf-8');

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

//$query  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* FROM tbl_tovar_order INNER JOIN `db_parser`.`tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`";
//addWhere("","");
//echo "1 - " . $sql;


//if (!$result) die ("Database access failed: " . mysql_error());

if (isset($_POST['u_count']) && isset($_POST['id_tovar_order']))
{
    $id_tovar_order = get_post('id_tovar_order');
    $u_count = get_post('u_count');
    $query = "UPDATE tbl_tovar_order SET count='$u_count' WHERE id_tovar_order='$id_tovar_order'";
    //echo $query;
    if (!mysql_query($query, $db_server))
        echo "Update failed: $query<br>" .
            mysql_error() . "<br><br>";
}

$result = mysql_query($sql);
$rows = mysql_num_rows($result);

?>

<?php
echo "<table border=\"1\"> <tr><td>№ Заказа</td><td>Дата заказа</td><td>Фото</td><td>Товар</td><td>Контакт</td><td>Сумма заказа,у.е.</td><td>Количество партий</td><td>Количество шт</td><td>Цена за ед., у.е.</td><td>Статус заказа</td><td>Статус отмены</td><td>Снимок заказа</td></tr>";

for ($j = 0 ; $j < $rows ; ++$j)
{
    $row = mysql_fetch_assoc($result);
    echo "<tr>";

        echo  <<<_END
        <td><a href="http://trade.aliexpress.com/order_detail.htm?orderId=$row[namber_order]" target="_blank" data-spm-anchor-id="0.0.0.0">$row[namber_order]</a></td>
_END;
        echo "<td>" . date("Y-m-d", $row[date_order]) . "</td>";
        echo "<td><img src=\"image.php?id=" . $row[id_tovar_order] . "\" alt=\"\" /></td>";
        echo "<td>$row[name]</td>";
        echo "<td>$row[manager]</td>";
        echo "<td>$row[price]</td>";
        echo "<td>$row[count_partiy]</td>";
        //echo "<td>$row[9]</td>";
        echo <<<_END
        <form name="update_count" action="" method="post">
            <td><input type="hidden" name="id_tovar_order" value=$row[id_tovar_order]>
            <input type="text" name="u_count" value="$row[count]">
            <input type="submit" value="Об-ть кол-во"></td>
        </form>
_END;
        echo "<td>" . number_format($row[price]*$row[count_partiy]/$row[count], 2, '.', ' ') . "</td>";
        echo "<td>$row[status]</td>";
        echo "<td>$row[status_otmeni]</td>";
        echo "<td>$row[snapshot]</td>";

    echo "</tr>";
}
echo "</table>";

function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
}

?>
