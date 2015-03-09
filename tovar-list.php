<form name="form" action="" method="post">
    <table>
        <tr>
            <td>Заказ   <input type="text" name="find_order" /></td>
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
    $sql  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* , `tbl_order`.`status` FROM tbl_tovar_order INNER JOIN `db_parser`.`tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`";
    if ($where) $sql .= " WHERE $where";
    echo $sql;
}
?>

<?php
header('Content-type: text/html; charset=utf-8');

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

//$query  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* FROM tbl_tovar_order INNER JOIN `db_parser`.`tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`";
//addWhere("","");
$result = mysql_query($sql);

if (!$result) die ("Database access failed: " . mysql_error());

$rows = mysql_num_rows($result);

?>

<?php
echo "<table border=\"1\"> <tr><td>№ Заказа</td><td>Дата заказа</td><td>Фото</td><td>Товар</td><td>Контакт</td><td>Сумма заказа,у.е.</td><td>Количество партий</td><td>Количество шт</td><td>Статус заказа</td></tr>";

for ($j = 0 ; $j < $rows ; ++$j)
{
    $row = mysql_fetch_row($result);
    echo "<tr>";

    for ($k = 0 ; $k < 12 ; ++$k)
        if (($k != 2)  and ($k != 3) and ($k != 10))
            echo "<td>$row[$k]</td>";

    echo "</tr>";
}

echo "</table>";
?>
