<?php

class Model_Tovarlist extends Model
{
	
	public function get_data($data)
	{
		//в массиве $data передаем данные из _POST контроллера Controller_Tovarlist
		//
		//
		//
		if(is_array($data)) {
			// преобразуем элементы массива в переменные
			extract($data);
		}
	
		///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
		require_once 'login.php';
		$db_server = mysql_connect($db_hostname, $db_username, $db_password);

		if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

		mysql_select_db($db_database, $db_server)
		or die("Unable to select database: " . mysql_error());

		mysql_query("SET NAMES utf8");


		// Главный запрос на выборку без Where и Order by
		$sql  = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*, tbl_tovar.id_virtuemart, tbl_tovar.NameVirtuemart
		FROM ((tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali) INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order) LEFT JOIN tbl_tovar ON tbl_order_tovar.ali_id_tovar = tbl_tovar.id_import";
		echo "Фильтр по товару" . $filtr_tovar . "</br>";
		//Добавляем условия к основному запросу в при нажатии разных кнопок
		if (!empty($_POST["filter"]) or !empty($_POST["filtr_by_order"]) or !empty($_POST["u_count"]) or !empty($_POST["refresh_status"]) or !empty($_POST['vm_update_count'])) {
			$where = "";
			if ($_POST["begin_date"]) $where = addWhere($where, "`date_order` >= '".htmlspecialchars(strtotime($_POST["begin_date"])))."'";
			if ($_POST["end_date"]) $where = addWhere($where, "`date_order` <= '".htmlspecialchars(strtotime($_POST["end_date"] . '23:59:59')))."'";
			if (!empty($filtr_order)) $where = addWhere($where, "`tbl_order`.`namber_order` = '" . htmlspecialchars($filtr_order) . "'");
			//if ($_POST["find_tovar"]) $where = addWhere($where, "`name` like '%" . htmlspecialchars($_POST["find_tovar"])) . "%'";
			if (!empty($filtr_tovar)) $where = addWhere($where, "`name` like '%" . htmlspecialchars($filtr_tovar) . "%'");
			/*$sql  = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*
			FROM (tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali) INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order";*/
			
			/*if ($where) {
					$sql .= " WHERE $where and `status_otmeni` !=" . "'<span>Order Cancelled</span>'";
					}
				else { 
					$sql .= " WHERE `status_otmeni` !=" . "'<span>Order Cancelled</span>'";
					}*/
			if ($where) $sql .= " WHERE " . $where;
			$sql .= " ORDER BY `tbl_order`.`date_order` DESC";
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
			//$sql  = "SELECT `tbl_order`.*, `tbl_order_tovar`.*  FROM tbl_order_tovar INNER JOIN `tbl_order` ON `tbl_order_tovar`.`namber_order` = `tbl_order`.`namber_order`". $where_cookie . " ORDER BY `tbl_order`.`namber_order` DESC";
			/*$sql  = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*
			FROM (tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali) INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order". $where_cookie . " ORDER BY `tbl_order`.`date_order` DESC";*/
			$sql  = $sql . $where_cookie . " ORDER BY `tbl_order`.`date_order` DESC";
			
			//echo "2 " . $sql;
		}

		//Отображаем только последние 100 записей, если выбран соответствующий радиобар в шапке страници
		//Нужно что бы быстрее обновлялась страница и не нагружался сервыер без причины
		if ($_POST["AllRecord"] != 2) {
			$sql = $sql . " LIMIT 100";
		}
				$result = mysql_query($sql);
				
				
				// Здесь мы просто сэмулируем реальные данные.
				$row = mysql_fetch_assoc($result);
				
				return $row

}



		///////////////////////////////////// Функциия для условия where в запросе на выборку
		function addWhere($where, $add, $and = true) {
			if ($where) {
				if ($and) $where .= " AND $add";
				else $where .= " OR $add";
			}
			else $where = $add;
			return $where;
		}
