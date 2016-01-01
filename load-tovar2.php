<?php
header('Content-type: text/html; charset=utf-8');
?>

<form name="form" action="" method="post">
    <input type="hidden" name="zagruzka" value="yes">
    <table>
        <tr>
            <td>№ нач страницы<input type="text" name="stranica_begin" value="17"/></td>
            <td>№ кон страницы<input type="text" name="stranica_end" value="17"/></td>
            <td colspan="2">
                <input type="submit" name="cmdLoadFromSave" value="Загрузить" />
				<input type="submit" name="cmdLoadFromBrowser" value="Загрузить с браузера" />
            </td>
        </tr>
    </table>
</form>

<?php
//header('Content-type: text/html; charset=utf-8');
//подгружаем библиотеку
require_once 'library/simple_html_dom.php';


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

/*$query = "DELETE FROM tbl_tovar_order";

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
		    $result = get_web_page("http://trade.aliexpress.com/orderList.htm");
			echo $result['errno'];
			if (($result['errno'] != 0 )||($result['http_code'] != 200))
				{
					echo $result['errmsg'];
				}
				else
				{
				    $page = $result['content'];
					echo "Загружаю страницу";
					//echo $page;
					$html = str_get_html($page);
				}

		}
		else
		{
			$html = file_get_html('www-ali/ali_order_' . $i . '.htm');
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
						$blob_picture = str_replace('src="', 'src="www-ali/', $product->find('a img', 0)->outertext);
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
								
                $txt_name_tovar = $product->find('.product-title', 0)->plaintext; //+
                //$txt_manager = $product->find('.seller-sign', 0)->plaintext;
                $dec_price = $product->find('.product-amount span', 0)->outertext;
                $dec_price = mb_substr($dec_price, mb_strpos($dec_price, '$') + 2, mb_strlen($dec_price) - 2); //+
                $int_count = $product->find('.product-amount', 0)->plaintext;
				$int_count = mb_substr($int_count, mb_strpos($int_count, 'X')+1, mb_strlen($dec_price)); //+
                $txt_status = $product->find('.f-left', 0)->outertext; //+
                $txt_status_otmeni = $product->find('.product-action span', 0)->outertext; //?
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
					$dt_date=strtotime($order->find('.second-row .info-body', 0)->plaintext);
                    //$txt_contacts = $order->find('.feedback', 0)->outertext;
                    $dec_summa = $order->find('.amount-num', 0)->plaintext;
                    $dec_summa = mb_substr($dec_summa, mb_strpos($dec_summa, '$') + 2, mb_strlen($dec_summa) - 2);

                    echo "<tr><td>" . $int_order . "</td>";
                    echo "<td>" . date("Y-m-d", $dt_date) . "</td>";
                    echo "<td>" . $txt_contacts . "</td>";
                    echo "<td>" . $dec_summa . "</td>";
                    echo "<td>" . $txt_status . "</td>";


                    $query = "SELECT * FROM tbl_order WHERE namber_order='" . $int_order . "'";
                    //echo $query;

                    echo $query;
                    $result = mysql_query($query, $db_server);
                    $rows = mysql_num_rows($result);
                    echo " - " . $rows;
                    if ($rows == 1) { //zapis uge est', update him
                        echo '-a запись обновлена'  . "</br>";
                        $query = "UPDATE tbl_order SET status='$txt_status' WHERE namber_order='$int_order'";

                        if (!mysql_query($query, $db_server))
                            echo "UPDATE failed: $query<br>" . mysql_error() . "<br><br>";
                    }
                    else {
                        echo '-a запись вставлена'  . "</br>";
                        $query = "INSERT INTO tbl_order VALUES" .
                            "('', '$int_order', '$dt_date', '$txt_contacts', '$dec_summa', '$txt_status','')";

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
                echo "<td>" . $txt_manager . "</td>";
                //echo $product->find('.sell-sp-main',0)->outertext . "</td>";
                echo "<td>" . $dec_price . "</td>";
                echo "<td>" . $int_count . "</td>";
                echo "<td>" . $txt_status_otmeni . "</td>";
                echo "<td>" . $bl_mobile . "</td>";
                echo "<td>" . $txt_snapshot . "</td>";
                echo "<td>" . $txt_snapshot_num . "</td></tr>";

                $query = "SELECT * FROM tbl_tovar_order WHERE snapshot_num='" . $txt_snapshot_num . "'";
                //$query = "SELECT * FROM tbl_tovar_order";
                //echo $query;
                $result = mysql_query($query, $db_server);
                $rows = mysql_num_rows($result);
                //echo " - " . $rows;
                if ($rows == 1) { //zapis uge est', update him
                    //echo 'Обновление статуса существующей записи товара'  . "</br>";
					//echo $txt_status_otmeni;
                    $query = "UPDATE tbl_tovar_order SET status_otmeni='$txt_status_otmeni' WHERE snapshot_num=$txt_snapshot_num";
					//echo $query;
                    if (!mysql_query($query, $db_server))
                        echo "UPDATE failed: $query<br>" . mysql_error() . "<br><br>";
                }
                else {
                    //echo 'second'  . "</br>";
					//echo $txt_status_otmeni;
                    $query = "INSERT INTO tbl_tovar_order VALUES" .
                        "('', '$int_order', '$blob_picture', '$txt_name_tovar', '$txt_manager', '$dec_price', '$int_count', '$int_count', 
						'$txt_status_otmeni', '$txt_snapshot', '$txt_snapshot_num', '$bl_mobile')";

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

function get_post($var)
{
    return mysql_real_escape_string($_POST[$var]);
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