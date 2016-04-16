<?php
//header('Content-type: text/html; charset=utf-8');
ini_set("max_execution_time", "120"); //увеличиваем допустимое время выполнения скрипта

//подгружаем библиотеку
require_once 'library/simple_html_dom.php';

///////////////////////////////////////////Запоминаем данные в сессию
//session_start();
$_SESSION['filtr_AllRecord'] = $_POST["AllRecord"];
$_SESSION['filtr_tovar'] = $_POST["find_tovar"];
//if (empty($_POST["refresh_count"])) $_SESSION['filtr_order'] = fOrder($_POST["find_order"],$_POST["namber_order"]);
if (empty($_POST["refresh_count"])) $_SESSION['filtr_order'] = $_POST["find_order"];
$_SESSION['filtr_begin_date'] = $_POST["begin_date"];
$_SESSION['filtr_end_date'] = $_POST["end_date"];

/////////////////////////////////////////// Обнуляем сессию если нажата кнопка Удалить фильтр
if (!empty($_POST["delfilter"])) {
$_SESSION['filtr_AllRecord']="";
$_SESSION['filtr_tovar'] = "";
$_SESSION['filtr_order'] = "";
$_SESSION['filtr_begin_date'] = "";
$_SESSION['filtr_end_date'] = "";
}

//////////////////////////////////////////Присваиваем перемменным значения сессии
$filtr_AllRecord = htmlspecialchars($_SESSION['filtr_AllRecord']);
$filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']);
$filtr_order = htmlspecialchars($_SESSION['filtr_order']);
$filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']);
$filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']);


echo <<<_END
  <style>
   p {
    margin-top: 0em; /* Отступ сверху */
    margin-bottom: 0em; /* Отступ снизу */
   }
  </style>
_END;

    //$vm_id_tovar = $_POST['vm_id_tovar'];
    //$u_count = $_POST['u_count'];
	//echo "sfdsd=" . $vm_id_tovar;
echo <<<_END
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

   $('[id*=but1_]').click(function(){
   var id=$(this).attr('id');
   var prefix=id.substring(id.indexOf("_")+1);
   var vm_id_tovar = $('#id_tovar_'+prefix).val();
   var id_tovar_order = $('#id_tovar_order_'+prefix).val();
   var u_count = $('#count_'+prefix).val();
    $.ajax({
		type:"POST",
		url:"vm-count-update.php", 
        data:"vm_id_tovar=" + vm_id_tovar + "&u_count=" + u_count + "&id_tovar_order="+id_tovar_order, 
        success:function(result){
        	$('#par1_'+prefix).html(result)
			$('#'+id).fadeOut(1000);
			$('#tovar_'+prefix + ' #chk_oprih_vm').attr("checked", true);
			/*alert('#'+id + ' Код товра='+ vm_id_tovar + ' Кол-во товара=' + u_count)*/},
		error: function(){
        alert('error')
		}
      });
	return false;
   });
});

$(document).ready(function(){

   $('[id*=but2_]').click(function(){
   var id=$(this).attr('id');
   var prefix=id.substring(id.indexOf("_")+1);
   var id_tovar_order = $('#id_tovar_order_'+prefix).val();
   var u_count = $('#count_'+prefix).val();
    $.ajax({
		type:"POST",
		url:"count-update.php", 
        data:"id_tovar_order=" + id_tovar_order + "&u_count=" + u_count, 
        success:function(result){
			/*alert('#'+id + ' Код товра='+ $('#sum_'+prefix).html() + ' Кол-во товара=' + $('#count_partiy_'+prefix).html() + ' Произведение' + $('#sum_'+prefix).html()*$('#count_partiy_'+prefix).html()/u_count)*/
			$('#price_'+prefix).html(number_format($('#sum_'+prefix).html()*$('#count_partiy_'+prefix).html()/u_count, 2, '.', ' '))
        	/*$('#par1_'+prefix).html(result)
			alert('#'+id + ' Код товра='+ id_tovar_order + ' Кол-во товара=' + u_count)*/},
		error: function(){
        alert('error')
		}
      });
	return false;
   });
});

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

</script>                    
_END;

/////////////////////////////////////////// Форма фильтра

if ($filtr_AllRecord==2) $str_AllRecord = '<input type="radio" name="AllRecord" id="AllRecord1" value=1 /><label for="AllRecord1">Последние 100 записей</label> <input type="radio" checked="checked" name="AllRecord" id="AllRecord2" value=2 /><label for="AllRecord2">Все записи&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>';
else $str_AllRecord = '<input type="radio" checked="checked" name="AllRecord" id="AllRecord1" value=1 /><label for="AllRecord1">Последние 100 записей</label> <input type="radio" name="AllRecord" id="AllRecord2" value=2 /><label for="AllRecord2">Все записи&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>';

//echo($_POST["AllRecord"]);

echo <<<_END
<form name="form" action="" method="post">
    <table>
        <tr>
			<td>$str_AllRecord</td>
            <td>Заказ   <input type="text" name="find_order" value = $filtr_order></td>
            <td>Нач дата (ДД.ММ.ГГГГ)<input type="text" name="begin_date" value = $filtr_begin_date></td>
            <td>Кон дата (ДД.ММ.ГГГГ)<input type="text" name="end_date" value = $filtr_end_date></td>
            <td>Товар   <input type="text" name="find_tovar" value = $filtr_tovar></td>
            <td colspan="2">
                <input type="submit" name="filter" value="Фильтр" />
				<input type="submit" name="delfilter" value="Удалить Фильтр" />
				<input type="submit" name="RefreshAllStatus" value="Обновить всем статус" />
            </td>
        </tr>
    </table>
</form>
_END;


///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

///////////////////////////////////// Отрабатывем кнопку обновить статус
if (!empty($_POST["refresh_status"])) load_status($_POST['find_order'],$db_server,$_POST['id_ali']);

////////////////////////////////////// Отрабатывваем кнопку Обновить всем статус
if (isset($_POST['RefreshAllStatus']))
{
    //$query = "SELECT tbl_order.id_ali, tbl_order.namber_order FROM tbl_order WHERE (((tbl_order.status)<>'Завершено' And (tbl_order.status)<>'Закрыт') AND ((tbl_order.date_order2)<' 	2015-12-27'));";
	$query = "SELECT tbl_order.id_ali, tbl_order.namber_order FROM tbl_order WHERE (((tbl_order.status)<>'Завершено' And (tbl_order.status)<>'Закрыт'));";
	$result = mysql_query($query);
	    if (!mysql_query($query, $db_server))
        echo "Update failed: $query<br>" .
            mysql_error() . "<br><br>";
	$rows = mysql_num_rows($result);

	for ($j = 0 ; $j < $rows ; ++$j)
	{
		$row = mysql_fetch_assoc($result);
			echo "Номер заказа $row[namber_order] </br>";
			load_status($row[namber_order],$db_server,$row['id_ali']);
	}
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

//////////////////////////////////// Отрисовывем главную таблицу
$result = mysql_query($sql);
$rows = mysql_num_rows($result);

echo "<table border=\"1\"> <tr><td>ali-логин</td><td>№ Заказа</td><td>Дата заказа</td><td>Фото</td><td>Товар</td><td>Наименование в Viruemart</td><td>Контакт</td><td>Сумма заказа,у.е.</td><td>Кол-во партий</td><td>Количество шт</td><td>Цена за ед., у.е.</td><td>Оприх-ть vm</td><td>Статус заказа</td><td>Статус отмены</td><td>Снимок заказа</td></tr>";

for ($j = 0 ; $j < $rows ; ++$j)
{
//echo "<div id='tovar_$j'>";
    $row = mysql_fetch_assoc($result);
    echo "<tr id='tovar_$j'>";
		echo "<td>$row[aliUserName]</br>
				<a href=\"http://services.ukrposhta.ua/bardcodesingle/Default.aspx?id=$row[tracknumber]\" target=\"_blank\">$row[tracknumber]</a>
			 </td>";
        echo  <<<_END
        <td><a href="http://trade.aliexpress.com/order_detail.htm?orderId=$row[namber_order]" target="_blank" data-spm-anchor-id="0.0.0.0">$row[namber_order]</a>
		<form name="cmd_filtr_order" action="" method="post">
            <input type="hidden" name="id_tovar_order" value=$row[id_tovar_order]>
            <input type="hidden" name="find_order" value=$row[namber_order]>
            <input type="submit" name="filtr_by_order" value="Фильтр">
        </form>
		</td>
_END;
        echo "<td>" . date("d.m.Y", $row["date_order"]) . "</td>";
        echo "<td><img src=\"image.php?id=" . $row["id_tovar_order"] . "\" alt=\"\" /></td>";
        echo "<td>$row[name]</td>";
		echo "<td>$row[NameVirtuemart]</td>";
		echo "<td><a href=\"http://ru.aliexpress.com/store/$row[ali_id_store]\">$row[store]</a></td>";
        echo "<td id='sum_$j'>$row[price]</td>";
        echo "<td id='count_partiy_$j'>$row[count_partiy]</td>";
        //echo "<td>$row[9]</td>";
        echo <<<_END
		<td>
        <input id="id_tovar_order_$j" type="hidden" value=$row[id_tovar_order]>
		<input id="count_$j" type="text" value="$row[count]">
        <input id="but2_$j"  type="button" value="Об-ть кол-во">
		</td>
_END;
        echo "<td id='price_$j'>" . number_format($row["price"]*$row["count_partiy"]/$row["count"], 2, '.', ' ') . "</td>";
		echo "<td>";
			if ($row[oprihodovan_vm]==-1) echo "<input id = 'chk_oprih_vm' type='checkbox' checked='checked' disabled='disabled' />";
			else {
				echo "<input id = 'chk_oprih_vm' type='checkbox' disabled='disabled' />";
				echo "<input id='id_tovar_$j' type='hidden' name='vm_id_tovar' value=$row[id_virtuemart]>";
				echo "<input id='but1_$j' type='button' value='Оприх-ть в VM'>";
				echo "<p id='par1_$j'>Не оприходован</p>";
			}
		echo "</td>";
        echo "<td>$row[status]";
        echo <<<_END
        <form name="update_status" action="" method="post">
            <input type="hidden" name="id_tovar_order" value=$row[id_tovar_order]>
            <input type="hidden" name="find_order" value=$row[namber_order]>
			<input type="hidden" name="id_ali" value=$row[id_ali]>
            <input type="submit" name="refresh_status" value="Об-ть статус"></td>
        </form>
_END;

        If ($row["status_otmeni"]=="Открыть спор") {
		echo <<<_END
			<td><a href="http://trade.aliexpress.com/order_detail.htm?orderId=$row[namber_order]" target="_blank">$row[status_otmeni]</a></td>
_END;
		//echo "<td>$row[status_otmeni]</td>";
		}
		else {
			echo "<td>$row[status_otmeni]</td>";
		}
		echo <<<_END
		<td><a target="_blank" href="http://www.aliexpress.com/snapshot/$row[snapshot_num].html?orderId=$row[namber_order]">Скриншот</a></br>
		<a target="_blank" href="http://feedback.aliexpress.com/management/leaveFeedback.htm?parentOrderId=$row[namber_order]&isOrderCompleted=Y">Отзыв</a>
		</td>
_END;
        //echo "<td>$row[snapshot]</td>";

    echo "</tr>";
//echo "</div>";
}
echo "</table>";



function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
}



function load_status($namber_order,$db_server,$id_ali)
{
	
    $result = get_web_page("http://trade.aliexpress.com/order_detail.htm?orderId=$namber_order",$id_ali);
    echo $result['errno'];
    if (($result['errno'] != 0 )||($result['http_code'] != 200))
    {
        echo $result['errmsg'];
    }
    else
    {
        $page = $result['content'];
        echo "Загружаю страницу </br>";
        //echo $page;
		$html = new simple_html_dom();
		$html = str_get_html($page);
		$str_status = trim($html->find('.order-status', 0)->plaintext);
		if (!empty($str_status)) {
			//echo "load status - " . $namber_order;
			echo  <<<_END
			load status - <a href="http://trade.aliexpress.com/order_detail.htm?orderId=$namber_order" target="_blank" data-spm-anchor-id="0.0.0.0">$namber_order</a></br>
_END;
			//$str_magazine= $html->find('.user-name-text', 0)->outertext;
			$str_tracknumber= trim($html->find('td[class=no]', 0)->plaintext);
			
			$query = "UPDATE tbl_order SET status='$str_status', tracknumber='$str_tracknumber' WHERE namber_order=$namber_order";
			echo $query;
			if (!mysql_query($query, $db_server))
				echo "Update failed: $query<br>" . mysql_error() . "<br><br>";
			
			$html = $html->find('.product-table',0);
			//echo $html;
			foreach ($html->find('.order-bd') as $product) {
					$str_status_otmeni = trim($product->find('.trade-status', 0)->plaintext);
					if (strlen($str_status_otmeni)>50) { //Убираем лишнее в строке типа "Получено подтверждение 	     Открыть спор"
						$str_status_otmeni=mb_substr($str_status_otmeni, 0, mb_strpos($str_status_otmeni,' ',10,"UTF-8"),"UTF-8");
					}
					$txt_snapshot = trim($product->find('.desc a', 0)->outertext);
					$str_begin = mb_strpos($txt_snapshot,'snapshot')+9;
					$str_end = mb_strpos($txt_snapshot,'.html');
					$txt_snapshot_num = mb_substr($txt_snapshot,$str_begin,$str_end-$str_begin);
					//echo $txt_snapshot_num;
						
					$query = "UPDATE tbl_order_tovar SET status_otmeni='$str_status_otmeni' WHERE snapshot_num=$txt_snapshot_num";
					//echo $query;
					if (!mysql_query($query, $db_server))
						echo "Update failed: $query<br>" . mysql_error() . "<br><br>";
			}
		}
		else
			{
				echo "Статус заказа пустой, скорее всего не обновлены куки";
			}
	}
}


function get_web_page($url,$id_ali)
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
	if ($id_ali == 1) {
		curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies/Leonid/cookies.txt");
	} elseif ($id_ali == 2) {
		curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies/Dmitriy/cookies.txt");
	}
	//curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies.txt");
	//curl_setopt($curl, CURLOPT_COOKIE, "xman_t=Nhp88qU1tdk8j7dPsWtlfxRQVsBhJ7Nww6/TM6gdHWZkvSJXXZ9J6OdA2QT3HKxhQKWSkzVb6lU+PzMvlHMNcaJN0uOPRXZdqThhMRaRl/vUtP1OyUhoUsTFdk2oatrw5ADxZGl3jCigGQKS6W21kXOIYgsPGt26WdX53KDpNcscpme2qOyGdPa0psn1oHWEmasrKsoIgHRu5D05dqObvK55qhntZEtYwycuYq/Z2DU4KplSZeDyOxW6M05iYEX/Rw32VyIU9wNJlu/OZjD+WcQuSiP4daQMCZ9nblWf+mgSWk4V1ux+l2c0/sfFj0oQO1ZsxV2wRImnwvZejG1BKkABHwdelmks0q+gS9fIrOFjL2e9DH9hZNrhqbkdWWwEM2g34oWgjWs3OkXWSjUciriX3b6HQTD9SBtr+btSSvA/v8Y7hKwvfAN2Vs+MhzWSbB/lbEgN6uI9uun70lw1rPqD3tDAg0Hn404AuW0XelOXI4xoStuRMPtqew7VuaiGpeACrVVRW8/DhSvuuhYAYL4kS8zILJXpnaNyyVchUEeuxZLNFjgtOgoR6T6LjRsEIX3XKtMimvm8VJ7pxAM0AXx/f6XaonA0yiRki6bdSGAEu3KPxBwr/cM9fCnT+MRq");


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

/*function fOrder ($find_order,$namber_order) 
{
if (!empty($find_order)):
		return $find_order;
	elseif (!empty($namber_order)):
		return $namber_order;
	else:
		return null;
endif;
}*/

?>