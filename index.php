<?php
header('Content-type: text/html; charset=utf-8');
//подгружаем библиотеку
require_once 'library/simple_html_dom.php';
//создаём новый объект
$html = new simple_html_dom();
$html_2 = new simple_html_dom();
//загружаем в него данные
$html = file_get_html('www-ali/ali_order_2.htm');
//находим все ссылки на странице и...

/*foreach($html->find('a') as $element)
       echo $element->href . '<br>'; */
/*	   
// Dumps the internal DOM tree back into string
$str = $html;

// Print it!
echo $html; 	*/

///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database, $db_server)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

$query = "DELETE FROM tbl_order";

if (!mysql_query($query, $db_server))
    echo "DELETE failed: $query<br>" . mysql_error() . "<br><br>";

$query = "DELETE FROM tbl_tovar_order";

if (!mysql_query($query, $db_server))
    echo "DELETE failed: $query<br>" . mysql_error() . "<br><br>";

///////////////////////////////// END Ponkluchaem BD //////////////////////////////
?>


  <table border="1">
   <caption>Парсер заказов aliexpress</caption>
   <tr>
    <th>№ Заказа</th>
    <th>Дата заказа</th>
    <th>Контакт</th>
    <th>Сумма заказа,у.е.</th>
   </tr>
<?php

foreach($html->find('.ae-order') as $order)
{
    $k=1;

         foreach ($order->find('.order-bd') as $product)
	    {
            $blob_picture = str_replace('src="','src="www-ali/',$product->find('a img',0)->outertext);
            $blom_image=$blob_picture;
            $blob_picture = mb_substr($blob_picture,mb_strpos($blob_picture,'src="')+5,mb_strlen($blob_picture));
            $blob_picture = str_replace('" alt="">','',$blob_picture);

            $fileName = $blob_picture;  //имя файла
            $f = fopen($fileName,"r");   //открываем файл
            $read = fread($f,filesize($fileName));  //считываем содержимое
            fclose($f);  //закрываем файл
            $blob_picture = addslashes($read);

            $txt_name_tovar = $product->find('.desc',0)->plaintext;
            $txt_manager = $product->find('.seller-sign',0)->plaintext;
            $dec_price = $product->find('.price',0)->outertext;
            $dec_price = mb_substr($dec_price,mb_strpos($dec_price,'$')+2 ,mb_strlen($dec_price)-2);
            $int_count = $product->find('.quantity',0)->plaintext;
            $txt_status = $product->find('.f-left',0)->outertext;
            $txt_snapshot = $product->find('.desc a',0)->outertext;
            $txt_snapshot = str_replace(trim($txt_name_tovar),'Снимок заказа',$txt_snapshot);

            if ($k == 1) {

                $int_order = $order->find('.order-num',0)->plaintext . "</td>";
                $tmp_date=mb_substr($order->find('.deal-time',0)->plaintext,26,18);
                $dt_date = strtotime($tmp_date); // переводит из строки в дату
                $txt_contacts = $order->find('.feedback',0)->outertext;
                $dec_summa = $order->find('.amount strong',0)->plaintext;
                $dec_summa = mb_substr($dec_summa,2,mb_strlen($dec_summa)-2);

                echo "<tr><td>" . $int_order .              "</td>";
                echo "<td>" . date("Y-m-d", $dt_date)  .    "</td>";
                echo "<td>" . $txt_contacts .               "</td>";
                echo "<td>" . $dec_summa .                  "</td>";
                echo "<td>" . $txt_status .                 "</td>";

                $query = "INSERT INTO tbl_order VALUES" .
                    "('', '$int_order', '$dt_date', '$txt_contacts', '$dec_summa', '$txt_status')";

                if (!mysql_query($query, $db_server))
                    echo "INSERT failed: $query<br>" . mysql_error() . "<br><br>";
                $k=0;

            }

			echo "<tr><td></td><td></td>";
            //header("Content-Type: image/jpg");  //указываем браузеру что это изображение
			echo "<td>" . $blom_image .       "</td>";
			echo "<td>" . $txt_name_tovar .     "</td>";
			echo "<td>" . $txt_manager .        "</td>";
			//echo $product->find('.sell-sp-main',0)->outertext . "</td>";
			echo "<td>" . $dec_price .          "</td>";
			echo "<td>" . $int_count .          "</td>";
			echo "<td>" . $product->find('.order-list-mobile-orders',0)->outertext . "</td>";
            echo "<td>" . $txt_snapshot .        "</td></tr>";

			//echo $product->outertext . '<br>';
			/*$pr = $product->find('.desc a',0)->href;
			echo $pr . '<br>';
			$var = shell_exec("curl -L -o D:\temp.html" . $pr); 
			$html_2 = file_get_html('D:\temp.html');
			echo $html_2->find('.company-name',0)->outertext . '<br>';*/
			
			/*foreach($html_2->find('a') as $a)
			{
				echo $a;
			}*/
			//echo $html_2;

            $query = "INSERT INTO tbl_tovar_order VALUES" .
                "('', '$int_order', '$blob_picture', '$txt_name_tovar', '$txt_manager', '$dec_price', '$int_count', '', '')";

            if (!mysql_query($query, $db_server))
                echo "INSERT failed: $query<br>" . mysql_error() . "<br><br>";

	     }
		//break;
}


//освобождаем ресурсы
$html->clear();
unset($html);
$html_2->clear();
unset($html_2);
?> 
