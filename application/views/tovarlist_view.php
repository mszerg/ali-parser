<style>
   p {
    margin-top: 0em; /* Отступ сверху */
    margin-bottom: 0em; /* Отступ снизу */
   }
</style>


<link rel="stylesheet" type="text/css" href="../../jquery-easyui-1.4.5/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../../jquery-easyui-1.4.5/themes/icon.css">
<link rel="stylesheet" type="text/css" href="../../jquery-easyui-1.4.5/demo/demo.css">
<script type="text/javascript" src="../../jquery-easyui-1.4.5/jquery.min.js"></script>
<script type="text/javascript" src="../../jquery-easyui-1.4.5/jquery.easyui.min.js"></script>

<script type="text/javascript" src="../../js/jquery.scannerdetection.js"></script>

<script type="text/javascript" src="/js/tovar_view_button.js"></script>
<script type="text/javascript" src="/js/jquery.session.js"></script>


<script type="text/javascript">
    $(document).scannerDetection(function(data)
    {
        $('#tracknumber').val(data);
        $.session.set("filtr_AllRecord", "1");
        $.session.set("filtr_tracknumber", data);
        //$.post( "/tovarlist/", { filter: "Фильтр"} );
        $('#form').submit();

    });

</script>

<?php
//header('Content-type: text/html; charset=utf-8');


//////////////////////////////////////////Присваиваем перемменным значения сессии
(isset($_SESSION["filtr_tracknumber"])  ? $filtr_tracknumber = htmlspecialchars($_SESSION['filtr_tracknumber']) : $filtr_tracknumber="");
(isset($_SESSION["filtr_AllRecord"])    ? $filtr_AllRecord = htmlspecialchars($_SESSION['filtr_AllRecord'])     : $filtr_AllRecord = 1); //по умолчанию выводим первые 100 записей, что бы не нагружать запрос
(isset($_SESSION["filtr_tovar"])        ? $filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']) 			: $filtr_tovar="");
(isset($_SESSION["filtr_order"])        ? $filtr_order = htmlspecialchars($_SESSION['filtr_order']) 			: $filtr_order="");
(isset($_SESSION["filtr_begin_date"])   ? $filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']) 	: $filtr_begin_date="");
(isset($_SESSION["filtr_end_date"])     ? $filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']) 		: $filtr_end_date="");

/////////////////////////////////////////// Форма фильтра ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($filtr_AllRecord==2) $str_AllRecord = '<input type="radio" name="AllRecord" id="AllRecord1" value=1 /><label for="AllRecord1">Последние 100 записей</label> <input type="radio" checked="checked" name="AllRecord" id="AllRecord2" value=2 /><label for="AllRecord2">Все записи&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>';
else $str_AllRecord = '<input type="radio" checked="checked" name="AllRecord" id="AllRecord1" value=1 /><label for="AllRecord1">Последние 100 записей</label> <input type="radio" name="AllRecord" id="AllRecord2" value=2 /><label for="AllRecord2">Все записи&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>';


//echo($_POST["AllRecord"]);

echo <<<_END
<form id="form" name="form" action="" method="post">
    <table>
        <tr>
			<td>$str_AllRecord</td>
			<td>Трек   <input id="tracknumber" type="text" name="find_tracknumber" value = $filtr_tracknumber></td>
            <td>Заказ   <input type="text" name="find_order" value = $filtr_order></td>
            <td>Нач дата (ДД.ММ.ГГГГ)<input type="text" name="begin_date" value = $filtr_begin_date></td>
            <td>Кон дата (ДД.ММ.ГГГГ)<input type="text" name="end_date" value = $filtr_end_date></td>
            <td>Товар   <input type="text" name="find_tovar" value = $filtr_tovar></td>
            <td colspan="2">
                <input id = "butFilter" type="submit" name="filter" value="Фильтр" />
				<input type="submit" name="delfilter" value="Удалить Фильтр" />
				<input id = "Refresh_All_Status" type="button" name="RefreshAllStatus" value="Обновить всем статус" />
            </td>
        </tr>
    </table>
</form>
_END;

echo "<p id='par4'></p>";

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
        echo "<td><img src=\"/image.php?id=" . $row["id_tovar_order"] . "\" alt=\"\" /></td>";
        echo "<td>$row[name]</td>";
        $str_json="[{'virtuemart_product_id':'1','product_name':'Arduino Leonardo'}]";
		echo "<td>
                <input id=\"cc_$j\" class='easyui-combobox' name=\"dept\" value='$row[id_virtuemart]'
                data-options = \"valueField:'virtuemart_product_id',
                               textField:'product_name',
                                data: [
                                    {virtuemart_product_id:'" . $row['id_virtuemart'] . "',product_name:'" . $row['NameVirtuemart'] . "'},
                                ],
                               icons:[{
                               iconCls:'icon-reload',handler:function(){
                                    $('#cc_$j').combobox('reload', '/tovarlist/get_vm_tovar')
                               }
                                    },{
                               iconCls:'icon-cancel',handler:function(){
                                        $.ajax({
                                            type:'POST',
                                            url:'/tovarlist/update_vm_tovar_null/',
                                            data:'ali_id_tovar=' +" . $row['ali_id_tovar'] . ",
                                            success:function(result){
                                                 /*alert(result)*/},
                                            error: function(){
                                                 alert('error')
                                                }
                                            });
                                        }
                                    }],
                               onSelect:function(rec){
                                    $.ajax({
                                    type:'POST',
                                    url:'/tovarlist/update_vm_tovar/',
                                    data:'newValue=' + rec.virtuemart_product_id + '&product_name=' + rec.product_name + '&ali_id_tovar=' + " . $row['ali_id_tovar'] . " + '&ali_name_tovar=' + '" . $row['name'] . "',
                                    success:function(result){
                                         /*alert(result)*/},
                                    error: function(){
                                         alert('error')
                                        }
                                    });
                                }\">
                                Коробка - ";
            printf('%.0F', (float)$row['product_packaging']);
        echo "</td>";
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