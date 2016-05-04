<?php
header('Content-type: text/html; charset=utf-8');
//подгружаем библиотеку
require_once 'library/simple_html_dom.php';

///////////////////////////////////////////Запоминаем данные в сессию
session_start();
$_SESSION['filtr_aliName'] = 		$_POST['aliName'];
$_SESSION['filtr_stranica_begin'] = $_POST["stranica_begin"];
$_SESSION['filtr_stranica_end'] = 	$_POST["stranica_end"];


//////////////////////////////////////////Присваиваем перемменным значения сессии
$int_aliName = 		htmlspecialchars($_SESSION['filtr_aliName']);
$post_stranica_begin = 	htmlspecialchars($_SESSION['filtr_stranica_begin']);
$post_stranica_end = 	htmlspecialchars($_SESSION['filtr_stranica_end']);

/////////////////////////////////////////// Форма фильтра
if ($int_aliName == 1 or empty($int_aliName)) {
	echo <<<_END
	<form name="form" action="" method="post">
		<input type="hidden" name="zagruzka" value="yes">
		<table>
			<tr>
				<td>
					<select name="aliName">
						<option disabled>Выберите ali-логин</option>
						<option selected value="1">Leonid</option>
						<option value="2">Dmitriy</option>
					</select>
				</td>
				<td>№ нач страницы<input type="text" name="stranica_begin" value=$post_stranica_begin></td>
				<td>№ кон страницы<input type="text" name="stranica_end" value=$post_stranica_end></td>
				<td colspan="2">
					<input type="submit" name="cmdLoadFromSave" value="Загрузить" />
					<input type="submit" name="cmdLoadFromBrowser" value="Загрузить с браузера" />
				</td>
			</tr>
		</table>
	</form>
_END;
} elseif ($int_aliName == 2) {
	echo <<<_END
	<form name="form" action="" method="post">
		<input type="hidden" name="zagruzka" value="yes">
		<table>
			<tr>
				<td>
					<select name="aliName">
						<option disabled>Выберите ali-логин</option>
						<option value="1">Leonid</option>
						<option selected value="2">Dmitriy</option>
					</select>
				</td>
				<td>№ нач страницы<input type="text" name="stranica_begin" value=$post_stranica_begin></td>
				<td>№ кон страницы<input type="text" name="stranica_end" value=$post_stranica_end></td>
				<td colspan="2">
					<input type="submit" name="cmdLoadFromSave" value="Загрузить" />
					<input type="submit" name="cmdLoadFromBrowser" value="Загрузить с браузера" />
				</td>
			</tr>
		</table>
	</form>
_END;
}

?>

<?php

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

/*$query = "DELETE FROM tbl_order";

if (!mysql_query($query, $db_server))
    echo "DELETE failed: $query<br>" . mysql_error() . "<br><br>";*/

/*$query = "DELETE FROM tbl_order_tovar";

if (!mysql_query($query, $db_server))
    echo "DELETE failed: $query<br>" . mysql_error() . "<br><br>";*/

///////////////////////////////// END Ponkluchaem BD //////////////////////////////
?>

<!--<div style="width:1024px; height:300px; overflow:auto">-->

  <table border="1">
   <caption>Парсер заказов aliexpress</caption>
   <tr>
    <th>№ Заказа</th>
    <th>Дата заказа</th>
    <th>Контакт</th>
    <th>Сумма заказа,у.е.</th>
   </tr>
<?php

$int_aliName=$_POST['aliName'];


if (!empty($_POST["zagruzka"]) && isset($_POST['stranica_begin']) && isset($_POST['stranica_end'])) {
    $stranica_begin = get_post('stranica_begin');
    $stranica_end = get_post('stranica_end');
	if (!empty($_POST["cmdLoadFromBrowser"]))
		{
		    $stranica_begin = 1;
			$stranica_end = 1;
		}
    for ($i = $stranica_begin; $i <= $stranica_end; ++$i) {
        //создаём новый объект
        //$i=1;
        //echo $i;
        $html = new simple_html_dom();
		//загружаем в него данные
        if (!empty($_POST["cmdLoadFromBrowser"])) 
		{
		    $result = get_web_page("http://trade.aliexpress.com/orderList.htm",$int_aliName);
			echo $result['errno'];
			if (($result['errno'] != 0 )||($result['http_code'] != 200))
				{
					echo $result['errmsg'];
				}
				else
				{
				    $page = $result['content'];
					echo "Загружаю страницу</br>";
					//echo $page;
					$html = str_get_html($page);
				}

		}
		else
		{
			if ($int_aliName == 1) {
				$html = file_get_html('www-ali/Leonid/ali_order_' . $i . '.htm');
			} elseif ($int_aliName == 2) {
				$html = file_get_html('www-ali/Dmitriy/ali_order_' . $i . '.htm');
			}
				
			//echo $html;
		}

        foreach ($html->find('.order-item-wraper ') as $order) {
            $k = 1;

            foreach ($order->find('.order-body') as $product) {
				///////////////////////////////////////////// Начало - Загружаем картинку товара /////////////////////////////////
				if (!empty($_POST["cmdLoadFromBrowser"]))
					{
						$blob_picture = $product->find('a img', 0)->outertext;
					}
				else
					//echo 'Загружаем картинку локально';
					{
						if ($int_aliName == 1) {
							$blob_picture = str_replace('src="', 'src="www-ali/Leonid/', $product->find('a img', 0)->outertext);
						} elseif ($int_aliName == 2) {
							$blob_picture = str_replace('src="', 'src="www-ali/Dmitriy/', $product->find('a img', 0)->outertext);
						}
					}
					$blob_image = $blob_picture;
					$blob_picture = mb_substr($blob_picture, mb_strpos($blob_picture, 'src="') + 5, mb_strlen($blob_picture));
					$blob_picture = str_replace('" alt="">', '', $blob_picture);
					//echo $blob_picture;
					$fileName = $blob_picture;  //имя файла
					$f = fopen($fileName, "rb");   //открываем файл
					if (!$f) {
						trigger_error('Не могу найти файл . $fileName');
						exit;
					}
				if (!empty($_POST["cmdLoadFromBrowser"]))
				{
					
					//$read = stream_get_contents($f);
					$read = '';
						while (!feof($f)) {
						$read .= fread($f, 8192);
						}
				}
				else
				{
					$read = fread($f, filesize($fileName));  //считываем содержимое
				}
                fclose($f);  //закрываем файл
                $blob_picture = addslashes($read);
				////////////////////////////////////////// Конец - Загружаем картинку товара /////////////////////////////////
								
                $txt_name_tovar = trim($product->find('.product-title', 0)->plaintext); //+
				$txt_tmp = $product->find('.product-title a', 0)->href;
				$txt_tmp = mb_substr($txt_tmp, mb_strpos($txt_tmp, '.html') - 14, 14);
				//echo $txt_tmp;
				$int_ali_id_tovar = mb_substr($txt_tmp, mb_strpos($txt_tmp, '/')+1, mb_strlen($txt_tmp));
                //$txt_manager = $product->find('.seller-sign', 0)->plaintext;
                $dec_price = $product->find('.product-amount span', 0)->outertext;
                $dec_price = mb_substr($dec_price, mb_strpos($dec_price, '$') + 2, mb_strlen($dec_price) - 2); //+
                $int_count = $product->find('.product-amount', 0)->plaintext;
				$int_count = mb_substr($int_count, mb_strpos($int_count, 'X')+1, mb_strlen($dec_price)); //+
                $txt_status = trim($product->find('.f-left', 0)->plaintext); //+
                $txt_status_otmeni = trim($product->find('.product-action span', 0)->plaintext); //?
				if (empty($txt_status_otmeni)) {
					$txt_status_otmeni = trim($product->find('.product-action a', 0)->plaintext);
				}
                $txt_snapshot = $product->find('.product-snapshot', 0)->outertext;
				//var_dump($txt_snapshot);
				
                $str_begin = mb_strpos($txt_snapshot,'/snapshot')+10;
                $str_end = mb_strpos($txt_snapshot,'.html');
                $txt_snapshot_num = mb_substr($txt_snapshot,$str_begin,$str_end-$str_begin);
				//$txt_snapshot_num  = $product->find('.product-snapshot', 0)->href;
                //$txt_snapshot = str_replace(trim($txt_name_tovar), 'Снимок заказа', $txt_snapshot);

                if (mb_strlen($product->find('.order-list-mobile-orders', 0)->plaintext) != 0) {
                    $bl_mobile = 1;
                } else {
                    $bl_mobile = 0;
                }
                //$bl_mobile=mb_strlen($product->find('.order-list-mobile-orders', 0)->plaintext);

                if ($k == 1) {

                    $int_order = $order->find('.info-body', 0)->plaintext . "</td>"; //+
					//$dt_date2=$order->find('.second-row .info-body', 0)->plaintext;
					$dt_date=strtotime($order->find('.second-row .info-body', 0)->plaintext);
					$dt_date2=date("Y-m-d h:i:s", $dt_date);
                    //$txt_contacts = $order->find('.feedback', 0)->outertext;
                    $dec_summa = $order->find('.amount-num', 0)->plaintext;
                    $dec_summa = mb_substr($dec_summa, mb_strpos($dec_summa, '$') + 2, mb_strlen($dec_summa) - 2);
					$txt_magazin_name = $order->find('.store-info .info-body', 0)->plaintext;
					$txt_magazin_url = $order->find('.second-row a', 0)->href;
					$int_magazin_number = mb_substr($txt_magazin_url, mb_strpos($txt_magazin_url, 'store')+6, mb_strlen($txt_magazin_url));
					

                    echo "<tr><td>" . $int_order . "</td>";
                    echo "<td>" . date("Y-m-d", $dt_date) . "</td>";
                    //echo "<td>" . $txt_magazin_url . "</td>";
					echo "<td>" . $int_magazin_number . "</td>";
                    echo "<td>" . $txt_magazin_name . "</td>";
					echo "<td>" . $dec_summa . "</td>";
                    echo "<td>" . $txt_status . "</td>";


                    $query = "SELECT * FROM tbl_order WHERE namber_order='" . $int_order . "'";
                    //echo $query;

                    echo $query;
                    $result = mysql_query($query, $db_server);
                    $rows = mysql_num_rows($result);
                    echo " - " . $rows;
                    if ($rows == 1) { //zapis uge est', update him
	                    /*echo '-a запись обновлена'  . "</br>";
                        $query = "UPDATE tbl_order SET id_ali='$int_aliName', date_order2='$dt_date2', ali_id_store='$int_magazin_number', store='" . mysql_escape_mimic($txt_magazin_name) . "', status='$txt_status' WHERE namber_order='$int_order'";*/
						$sql="SELECT status from tbl_order WHERE namber_order='$int_order'";
						$result2 = mysql_query($sql);
						$row2 = mysql_fetch_assoc($result2);
						if ($row2[status] !='Продавец отправил Ваш заказ') {
							echo '-a запись обновлена'  . "</br>";
							$query = "UPDATE tbl_order SET status='$txt_status' WHERE namber_order='$int_order'";
						}
						else {
							echo "-н Заказ уже отправлен</br>";
						}
						
                        if (!mysql_query($query, $db_server))
                            echo "UPDATE failed: $query<br>" . mysql_error() . "<br><br>";
                    }
                    else {
                        echo '-a запись вставлена'  . "</br>";
                        $query = "INSERT INTO tbl_order VALUES" .
                            "('', '$int_aliName', '$int_order', '$dt_date', '$dt_date2', '$int_magazin_number','" . mysql_escape_mimic($txt_magazin_name) . "', '$dec_summa','" . mysql_escape_mimic($txt_status) . "','','')";

                        if (!mysql_query($query, $db_server))
                            echo "INSERT failed: $query<br>" . mysql_error() . "<br><br>";
                    }
                    //echo "</div>";
                    $k = 0;

                }

                echo "<tr><td></td><td></td>";
                //header("Content-Type: image/jpg");  //указываем браузеру что это изображение
                echo "<td>" . $blob_image . "</td>";
                echo "<td>" . $txt_name_tovar . "</td>";
                //echo "<td>" . $txt_manager . "</td>";
                //echo $product->find('.sell-sp-main',0)->outertext . "</td>";
                echo "<td>" . $dec_price . "</td>";
                echo "<td>" . $int_count . "</td>";
                echo "<td>" . $txt_status_otmeni . "</td>";
                echo "<td>" . $bl_mobile . "</td>";
                echo "<td>" . $txt_snapshot . "</td>";
                echo "<td>" . $txt_snapshot_num . "</td></tr>";

                $query = "SELECT * FROM tbl_order_tovar WHERE snapshot_num='" . $txt_snapshot_num . "'";
                //$query = "SELECT * FROM tbl_order_tovar";
                //echo $query;
                $result = mysql_query($query, $db_server);
                $rows = mysql_num_rows($result);
                //echo " - " . $rows;
                if ($rows == 1) { //zapis uge est', update him
                    //echo 'Обновление статуса существующей записи товара'  . "</br>";
					//echo $txt_status_otmeni;
					//$query = "UPDATE tbl_order_tovar SET ali_id_tovar='$int_ali_id_tovar', status_otmeni='" . mysql_escape_mimic($txt_status_otmeni) . "' WHERE snapshot_num=$txt_snapshot_num";
					$sql="SELECT status_otmeni from tbl_order_tovar WHERE snapshot_num=$txt_snapshot_num";
					$result2 = mysql_query($sql);
					$row2 = mysql_fetch_assoc($result2);
					if ($row2[status_otmeni] !='Товар отправлен') {
						$query = "UPDATE tbl_order_tovar SET status_otmeni='" . mysql_escape_mimic($txt_status_otmeni) . "' WHERE snapshot_num=$txt_snapshot_num";
					}
					//$query = "UPDATE tbl_order_tovar SET name = '" . mysql_escape_mimic($txt_name_tovar) . "', ali_id_tovar='$int_ali_id_tovar', status_otmeni='" . mysql_escape_mimic($txt_status_otmeni) . "' WHERE snapshot_num=$txt_snapshot_num";
					
					//echo $query;
                    if (!mysql_query($query, $db_server))
                        echo "UPDATE failed: $query<br>" . mysql_error() . "<br><br>";
                }
                else {
                    //echo 'second'  . "</br>";
					//echo $txt_status_otmeni;
                    $query = "INSERT INTO tbl_order_tovar VALUES" .
                        "('', '$int_order', '$blob_picture', '$int_ali_id_tovar','" . mysql_escape_mimic($txt_name_tovar) . "', \"$txt_manager\", '$dec_price', '$int_count', '$int_count', '" . mysql_escape_mimic($txt_status_otmeni) . "', '$txt_snapshot_num', '$bl_mobile',0)";

                    if (!mysql_query($query, $db_server))
                        echo "INSERT failed: $query<br>" . mysql_error() . "<br><br>";
                }
				//break;
            }
            //break;
        }
		
        //echo "</div>";


//освобождаем ресурсы
        $html->clear();
        unset($html);
    } //end for
} //end if

// Функция убирает спецсимовлы из строки
function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\Z'), $inp);
    }

    return $inp;
} 

function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
}

function get_web_page($url,$int_aliName)
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
	if ($int_aliName == 1) {
		curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies/Leonid/cookies.txt");
	} elseif ($int_aliName == 2) {
		curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies/Dmitriy/cookies.txt");
	}
	//curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER[DOCUMENT_ROOT]."/cookies.txt");

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