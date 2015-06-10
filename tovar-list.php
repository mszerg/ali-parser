<?php
header('Content-type: text/html; charset=utf-8');

session_start();
$_SESSION['filtr_tovar'] = $_POST["find_tovar"];
$_SESSION['filtr_order'] = $_POST["find_order"];
$_SESSION['filtr_begin_date'] = $_POST["begin_date"];
$_SESSION['filtr_end_date'] = $_POST["end_date"];

if (!empty($_POST["refresh_status"])) load_status($_POST['id_order']);
if (!empty($_POST["filtr_by_order"])) $_SESSION['filtr_order'] = $_POST['id_order'];

$filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']);
$filtr_order = htmlspecialchars($_SESSION['filtr_order']);
$filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']);
$filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']);
echo <<<_END
<form name="form" action="" method="post">
    <table>
        <tr>
            <td>Заказ   <input type="text" name="find_order" value = $filtr_order></td>
            <td>Нач дата (ДД.ММ.ГГГГ)<input type="text" name="begin_date" value = $filtr_begin_date></td>
            <td>Кон дата (ДД.ММ.ГГГГ)<input type="text" name="end_date" value = $filtr_end_date></td>
            <td>Товар   <input type="text" name="find_tovar" value = $filtr_tovar></td>
            <td colspan="2">
                <input type="submit" name="filter" value="Фильтр" />
            </td>
        </tr>
    </table>
</form>
_END;


?>

<?php

function addWhere($where, $add, $and = true) {
    if ($where) {
        if ($and) $where .= " AND $add";
        else $where .= " OR $add";
    }
    else $where = $add;
    return $where;
}
if (!empty($_POST["filter"]) or !empty($_POST["filtr_by_order"]) or !empty($_POST["u_count"])) {
    $where = "";
    if ($_POST["begin_date"]) $where = addWhere($where, "`date_order` >= '".htmlspecialchars(strtotime($_POST["begin_date"])))."'";
    if ($_POST["end_date"]) $where = addWhere($where, "`date_order` <= '".htmlspecialchars(strtotime($_POST["end_date"] . '23:59:59')))."'";
    //if ($_POST["manufacturers"]) $where = addWhere($where, "`manufacturer` IN (".htmlspecialchars(implode(",", $_POST["manufacturers"])).")");
    //if ($_POST["find_order"]) $where = addWhere($where, "`wifi` = '1'");
	/*if (!empty($_POST["find_order"])) {
			//$res = $_POST["find_order"];
			$where = addWhere($where, "`namber_order` = '" . htmlspecialchars($_POST["find_order"]) . "'");
		}
		elseif (!empty($_POST["id_order"])) {
			//$res = $_POST["id_order"];
			$where = addWhere($where, "`namber_order` = '" . htmlspecialchars($_POST["id_order"]) . "'");
			}
		echo $filtr_order;*/
		
	//if ($_POST["find_order"]) $where = addWhere($where, "`namber_order` = '" . htmlspecialchars($filtr_order) . "'");
	if (!empty($filtr_order)) $where = addWhere($where, "`namber_order` = '" . htmlspecialchars($filtr_order) . "'");
    if ($_POST["find_tovar"]) $where = addWhere($where, "`name` like '%" . htmlspecialchars($_POST["find_tovar"])) . "%'";
    $sql  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* , `tbl_order`.`status` FROM tbl_tovar_order INNER JOIN `tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`";
    if ($where) {
			$sql .= " WHERE $where and `status_otmeni` !=" . "'<span>Order Cancelled</span>'";
			}
		else { 
			$sql .= " WHERE `status_otmeni` !=" . "'<span>Order Cancelled</span>'";
			}
    $sql .= " ORDER BY `namber_order` DESC";
	echo $sql;
}
else
{
    if ($filtr_tovar != '') {
			$where_cookie = " WHERE `name` like '%" . $filtr_tovar . "%' and `status_otmeni` !=" . "'<span>Order Cancelled</span>'";
		}
		else {
			$where_cookie = " WHERE `status_otmeni` !=" . "'<span>Order Cancelled</span>'";
		}
    //echo $where_cookie . "</br>";
    $sql  = "SELECT `tbl_order`.`namber_order`,`tbl_order`.`date_order`, `tbl_tovar_order`.* , `tbl_order`.`status` FROM tbl_tovar_order INNER JOIN `tbl_order` ON `tbl_tovar_order`.`id_order` = `tbl_order`.`namber_order`". $where_cookie . " ORDER BY `namber_order` DESC";
	//echo "2 " . $sql;
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

echo "<table border=\"1\"> <tr><td>№ Заказа</td><td>Дата заказа</td><td>Фото</td><td>Товар</td><td>Контакт</td><td>Сумма заказа,у.е.</td><td>Количество партий</td><td>Количество шт</td><td>Цена за ед., у.е.</td><td>Статус заказа</td><td>Статус отмены</td><td>Снимок заказа</td></tr>";

for ($j = 0 ; $j < $rows ; ++$j)
{
    $row = mysql_fetch_assoc($result);
    echo "<tr>";
        echo  <<<_END
        <td><a href="http://trade.aliexpress.com/order_detail.htm?orderId=$row[namber_order]" target="_blank" data-spm-anchor-id="0.0.0.0">$row[namber_order]</a>
		<form name="cmd_filtr_order" action="" method="post">
            <input type="hidden" name="id_tovar_order" value=$row[id_tovar_order]>
            <input type="hidden" name="id_order" value=$row[namber_order]>
            <input type="submit" name="filtr_by_order" value="Фильтр">
        </form></td>
_END;
        echo "<td>" . date("d-m-Y", $row[date_order]) . "</td>";
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
        echo "<td>$row[status]";
        echo <<<_END
        <form name="update_status" action="" method="post">
            <input type="hidden" name="id_tovar_order" value=$row[id_tovar_order]>
            <input type="hidden" name="id_order" value=$row[namber_order]>
            <input type="submit" name="refresh_status" value="Об-ть статус"></td>
        </form>
_END;
        echo "<td>$row[status_otmeni]</td>";
        echo "<td>$row[snapshot]</td>";

    echo "</tr>";
}
echo "</table>";



function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
}



function load_status($namber_order)
{
    echo "load status - " . $namber_order;
    echo  <<<_END
        <a href="http://trade.aliexpress.com/order_detail.htm?orderId=$namber_order" target="_blank" data-spm-anchor-id="0.0.0.0">$namber_order</a>
_END;
    $result = get_web_page("http://trade.aliexpress.com/order_detail.htm?orderId=64091038801401");
    echo $result['errno'];
    if (($result['errno'] != 0 )||($result['http_code'] != 200))
    {
        echo $result['errmsg'];
    }
    else
    {
        $page = $result['content'];
        echo "Загружаю страницу";
        echo $page;
    }

    /*require_once 'library/simple_html_dom.php';
    $html = new simple_html_dom();
    //$html = file_get_html("http://trade.aliexpress.com/order_detail.htm?orderId=" . $namber_order);
    $html = file_get_html("http://trade.aliexpress.com/order_detail.htm?orderId=64091038801401");
    echo $html->innertext;
    $txt_status = $html->find('.order-status', 0)->outertext;
    $txt_status_otmeni = $html->find('.trade-status', 0)->outertext;
    echo $txt_status . " -st1</br>";
    echo $txt_status_otmeni . " -st2</br>";

    $html->clear();
    unset($html);*/
}


function get_web_page($url)
{
    $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

    $ch = curl_init( $url );

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
    curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // переходит по редиректам
    curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа
	//curl_setopt($ch, CURLOPT_CAINFO, "./cacert.pem");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies.txt");

    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

?>