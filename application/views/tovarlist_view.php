<style>
   p {
    margin-top: 0em; /* Отступ сверху */
    margin-bottom: 0em; /* Отступ снизу */
   }
</style>

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

// Кнопка обновить статус
$(document).ready(function(){

    $('[id*=but3_]').click(function(){
        var id=$(this).attr('id');
        var prefix=id.substring(id.indexOf("_")+1);
        var find_order = $('#tovar_'+prefix + ' #find_order').val();
        var id_ali = $('#tovar_'+prefix + ' #id_ali').val();
        $.ajax({
            type:"POST",
            url:"/tovarlist/load_status/",
            data:"find_order=" + find_order + "&id_ali=" + id_ali,
            success:function(result){
                $('#tovar_'+prefix + ' #par3').html(result)
                /*$('#'+id).fadeOut(1000);
                $('#tovar_'+prefix + ' #chk_oprih_vm').attr("checked", true);
                alert('#'+id + ' find_order='+ find_order + ' id_ali=' + id_ali)*/},
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


<?php
//header('Content-type: text/html; charset=utf-8');
ini_set("max_execution_time", "120"); //увеличиваем допустимое время выполнения скрипта

//////////////////////////////////////////Присваиваем перемменным значения сессии
(isset($_SESSION["filtr_AllRecord"])    ? $filtr_AllRecord = htmlspecialchars($_SESSION['filtr_AllRecord']) : $filtr_AllRecord = 1); //по умолчанию выводим первые 100 записей, что бы не нагружать запрос
(isset($_SESSION["filtr_tovar"])        ? $filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']) : $filtr_tovar="");
(isset($_SESSION["filtr_order"])        ? $filtr_order = htmlspecialchars($_SESSION['filtr_order']) : $filtr_order="");
(isset($_SESSION["filtr_begin_date"])   ? $filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']) : $filtr_begin_date="");
(isset($_SESSION["filtr_end_date"])     ? $filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']) : $filtr_end_date="");


/////////////////////////////////////////// Форма фильтра ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

		///////////////////////////////////// Отрабатывем кнопку обновить статус
		//if (!empty($_POST["refresh_status"])) load_status($_POST['find_order'],$db_server,$_POST['id_ali']);
        //if (!empty($_POST["refresh_status"])) require_once 'library/simple_html_dom.php';

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

//////////////////////////////////// Отрисовывем главную таблицу
//$result = mysql_query($sql);
//$rows = mysql_num_rows($result);

echo "<table border=\"1\"> <tr><td>ali-логин</td><td>№ Заказа</td><td>Дата заказа</td><td>Фото</td><td>Товар</td><td>Наименование в Viruemart</td><td>Контакт</td><td>Сумма заказа,у.е.</td><td>Кол-во партий</td><td>Количество шт</td><td>Цена за ед., у.е.</td><td>Оприх-ть vm</td><td>Статус заказа</td><td>Статус отмены</td><td>Снимок заказа</td></tr>";
$j = 0;
//for ($j = 0 ; $j < $rows ; ++$j)
//for ($j = 0 ; $j < 100 ; ++$j)
foreach($data as $row)
//while ($row  = $data)
{
    ++$j;
    //$row=$rows[$j];
    //    var_dump($row);
    //echo "<div id='tovar_$j'>";
    //$row = mysql_fetch_assoc($result);
    //$row=$data;
    echo "<tr id='tovar_$j'>";
		echo "<td>" . $row['aliUserName'] . "</br>
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
			if ($row['oprihodovan_vm']==-1) echo "<input id = 'chk_oprih_vm' type='checkbox' checked='checked' disabled='disabled' />";
			else {
				echo "<input id = 'chk_oprih_vm' type='checkbox' disabled='disabled' />";
				echo "<input id='id_tovar_$j' type='hidden' name='vm_id_tovar' value=$row[id_virtuemart]>";
				echo "<input id='but1_$j' type='button' value='Оприх-ть в VM'>";
				echo "<p id='par1_$j'>Не оприходован</p>";
			}
		echo "</td>";
        echo "<td>$row[status]";
        echo <<<_END
        <input type="hidden" name="id_tovar_order" value=$row[id_tovar_order]>
        <input id="find_order" type="hidden" name="find_order" value=$row[namber_order]>
		<input id="id_ali" type="hidden" name="id_ali" value=$row[id_ali]>
        <input id="but3_$j" type="button" value="Об-ть статус">
        <p id='par3'></p></td>
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

?>