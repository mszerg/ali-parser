<?php
//header('Content-type: text/html; charset=utf-8');

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");
////////////////////////////////////// Обновляем количество товра на остатке в VM

//if (isset($_POST['vm_update_count']) && !empty($_POST['vm_id_tovar']))
if (!empty($_POST['vm_id_tovar']))
{
    $vm_id_tovar = get_post('vm_id_tovar');
    $u_count = get_post('u_count');
    $query = "UPDATE mszerg_autohome.v3t_virtuemart_products INNER JOIN tbl_tovar ON mszerg_autohome.v3t_virtuemart_products.virtuemart_product_id = mszerg_myfin.tbl_tovar.id_virtuemart SET mszerg_autohome.v3t_virtuemart_products.product_in_stock = mszerg_autohome.v3t_virtuemart_products.product_in_stock + $u_count WHERE (((v3t_virtuemart_products.virtuemart_product_id)=$vm_id_tovar))";
    //echo $query;
    if (!mysql_query($query, $db_server)) echo "Update failed: $query<br>" .  mysql_error() . "<br><br>";
	else echo "Оприходован";
	
	// Ставим отметку что товар уже оприходован в vm
	$id_tovar_order = get_post('id_tovar_order');
	$query = "UPDATE `tbl_order_tovar` SET `oprihodovan_vm`=-1 WHERE `id_tovar_order`=$id_tovar_order";
	if (!mysql_query($query, $db_server)) echo "Update failed: $query<br>" .  mysql_error() . "<br><br>";
}
else echo "Нет привязки к VM";



function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
}

?>