<?php

class Model_Tovarlist extends Model
{
	function __construct()
	{
		//Подключение к базе
		require_once 'login.php';
		$db_server = mysql_connect($db_hostname, $db_username, $db_password);

		if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

		mysql_select_db($db_database, $db_server)
		or die("Unable to select database: " . mysql_error());

		mysql_query("SET NAMES utf8");
	}
	
	public function get_data()
	{
		//в массиве $data передаем данные из _POST контроллера Controller_Tovarlist
		//
		//
		//
		/*if(is_array($data)) {
			// преобразуем элементы массива в переменные
			extract($data);
		}*/
	
		///////////////////////////////////////////Запоминаем данные в сессию
		//session_start();

        if (isset($_POST['AllRecord']))         $_SESSION['filtr_AllRecord'] = $_POST['AllRecord'];
        if (isset($_POST['find_tracknumber']))  $_SESSION['filtr_tracknumber'] = $_POST['find_tracknumber'];
        if (isset($_POST['find_tovar']))        $_SESSION['filtr_tovar'] = $_POST['find_tovar'];
		if (isset($_POST["find_order"]))        $_SESSION['filtr_order'] = $_POST["find_order"];
        if (isset($_POST['begin_date']))        $_SESSION['filtr_begin_date'] = $_POST["begin_date"];
        if (isset($_POST['end_date']))          $_SESSION['filtr_end_date'] = $_POST["end_date"];

		/////////////////////////////////////////// Обнуляем сессию если нажата кнопка Удалить фильтр
		if (isset($_POST["delfilter"])) {
            $_SESSION['filtr_AllRecord']="";
            $_SESSION['filtr_tracknumber']="";
            $_SESSION['filtr_tovar'] = "";
            $_SESSION['filtr_order'] = "";
            $_SESSION['filtr_begin_date'] = "";
            $_SESSION['filtr_end_date'] = "";
		}

//////////////////////////////////////////Присваиваем перемменным значения сессии
        (isset($_SESSION["filtr_tracknumber"])  ? $filtr_tracknumber = htmlspecialchars($_SESSION['filtr_tracknumber']) : $filtr_tracknumber="");
        (isset($_SESSION["filtr_AllRecord"])    ? $filtr_AllRecord = htmlspecialchars($_SESSION['filtr_AllRecord'])     : $filtr_AllRecord = 1); //по умолчанию выводим первые 100 записей, что бы не нагружать запрос
        (isset($_SESSION["filtr_tovar"])        ? $filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']) 			: $filtr_tovar="");
        (isset($_SESSION["filtr_order"])        ? $filtr_order = htmlspecialchars($_SESSION['filtr_order']) 			: $filtr_order="");
        (isset($_SESSION["filtr_begin_date"])   ? $filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']) 	: $filtr_begin_date="");
        (isset($_SESSION["filtr_end_date"])     ? $filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']) 		: $filtr_end_date="");

        // Главный запрос на выборку без Where и Order by
        /*$sql  = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*, tbl_tovar.id_virtuemart, tbl_tovar.NameVirtuemart
		FROM ((tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali) INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order) LEFT JOIN tbl_tovar ON tbl_order_tovar.ali_id_tovar = tbl_tovar.id_import";*/
        $sql  = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*, tbl_tovar.id_virtuemart, tbl_tovar.NameVirtuemart, mszerg_autohome.v3t_virtuemart_products.product_packaging
            FROM (((tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali)
            INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order)
            LEFT JOIN tbl_tovar ON tbl_order_tovar.ali_id_tovar = tbl_tovar.id_import)
            LEFT JOIN mszerg_autohome.v3t_virtuemart_products ON tbl_tovar.id_virtuemart = mszerg_autohome.v3t_virtuemart_products.virtuemart_product_id";

        //echo "Фильтр по товару: " . $filtr_tovar . "</br>";
        //Добавляем условия к основному запросу в при нажатии разных кнопок
        if (!empty($filtr_tracknumber) or !empty($_POST["filter"]) or !empty($_POST["filtr_by_order"]) or !empty($_POST["u_count"]) or !empty($_POST["refresh_status"]) or !empty($_POST['vm_update_count'])) {
            $where = "";
            if (!empty($filtr_tracknumber)) $where = $this->addWhere($where, "`tracknumber` = '".htmlspecialchars($_POST["find_tracknumber"]))."'";
            if (!empty($_POST["begin_date"])) $where = $this->addWhere($where, "`date_order` >= '".htmlspecialchars(strtotime($_POST["begin_date"])))."'";
            if (!empty($_POST["end_date"])) $where = $this->addWhere($where, "`date_order` <= '".htmlspecialchars(strtotime($_POST["end_date"] . '23:59:59')))."'";
            if (!empty($filtr_order)) $where = $this->addWhere($where, "`tbl_order`.`namber_order` = '" . htmlspecialchars($filtr_order) . "'");
            //if ($_POST["find_tovar"]) $where = $this->addWhere($where, "`name` like '%" . htmlspecialchars($_POST["find_tovar"])) . "%'";
            if (!empty($filtr_tovar)) $where = $this->addWhere($where, "`name` like '%" . htmlspecialchars($filtr_tovar) . "%'");
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
        if ($filtr_AllRecord != 2) {
            $sql = $sql . " LIMIT 100";
        }
        echo $sql;
        $result = mysql_query($sql);
        $arr = array();
        //echo $sql . "</br>". "</br>";

        while ( $row = mysql_fetch_array($result) ) $arr[] = $row;

        return $arr;


    }
	
	function load_status_params($namber_order,$id_ali)
    {
		ini_set("max_execution_time", "120"); //увеличиваем допустимое время выполнения скрипта
        require_once $_SERVER['DOCUMENT_ROOT'] . '/library/simple_html_dom.php';
		
		//echo "namber_order = " . $namber_order . " id = " . $id_ali;

        $result = $this->get_web_page("http://trade.aliexpress.com/order_detail.htm?orderId=$namber_order",$id_ali);
        //echo $result['errno'];
        if (($result['errno'] != 0 )||($result['http_code'] != 200))
        {
            echo $result['errmsg'];
        }
        else
        {
            $page = $result['content'];
        //    echo "Загружаю страницу </br>";
        //    echo $page;
            $html = str_get_html($page);
            (is_object($html->find('.order-status', 0)) ? $str_status=trim($html->find('.order-status', 0)->plaintext) : $str_status="");
            if (!empty($str_status)) {
                //echo "load status - " . $namber_order;
                /*echo  <<<_END
			load status - <a href="http://trade.aliexpress.com/order_detail.htm?orderId=$namber_order" target="_blank" data-spm-anchor-id="0.0.0.0">$namber_order</a></br>
_END;*/
                //$str_magazine= $html->find('.user-name-text', 0)->outertext;
                $str_tracknumber= trim($html->find('td[class=no]', 0)->plaintext);

                $query = "UPDATE tbl_order SET status='$str_status', tracknumber='$str_tracknumber' WHERE namber_order=$namber_order";
                //echo $query;
                if (!mysql_query($query))
                    echo "Update failed: $query<br>" . mysql_error() . "<br><br>";

                //$this->model->update_data($query);

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

                    $blob_picture = $product->find('a img', 0)->outertext;
                    $blob_picture = mb_substr($blob_picture, mb_strpos($blob_picture, 'src="') + 5, mb_strlen($blob_picture));
                    $fileName_picture_ali = str_replace('" alt="">', '', $blob_picture);




                    //$query = "UPDATE tbl_order_tovar SET status_otmeni='$str_status_otmeni' WHERE snapshot_num=$txt_snapshot_num";
                    $query = "UPDATE tbl_order_tovar SET status_otmeni='$str_status_otmeni', fileName_picture_ali= '$fileName_picture_ali' WHERE snapshot_num=$txt_snapshot_num";
                    //echo $query;
                    if (!mysql_query($query))
                        echo "Update failed: $query<br>" . mysql_error() . "<br><br>";
                    //$this->model->update_data($query);
                }
                echo "$namber_order - Удачно, об-те стр-цу" . "<br>";
            }
            else
            {
                echo "$namber_order - Ошибка, обновите куки" . "<br>";
            }
        }
    }
	
	function vm_count_update()
	{
		////////////////////////////////////// Обновляем количество товра на остатке в VM

		//if (isset($_POST['vm_update_count']) && !empty($_POST['vm_id_tovar']))
		if (!empty($_POST['vm_id_tovar']))
		{
			$vm_id_tovar = $this->get_post('vm_id_tovar');
			$u_count = $this->get_post('u_count');
			$query = "UPDATE mszerg_autohome.v3t_virtuemart_products INNER JOIN tbl_tovar ON mszerg_autohome.v3t_virtuemart_products.virtuemart_product_id = mszerg_myfin.tbl_tovar.id_virtuemart SET mszerg_autohome.v3t_virtuemart_products.product_in_stock = mszerg_autohome.v3t_virtuemart_products.product_in_stock + $u_count WHERE (((v3t_virtuemart_products.virtuemart_product_id)=$vm_id_tovar))";
			//echo $query;
			if (!mysql_query($query)) echo "Update failed: $query<br>" .  mysql_error() . "<br><br>";
			else echo "Оприходован";
			
			// Ставим отметку что товар уже оприходован в vm
			$id_tovar_order = $this->get_post('id_tovar_order');
			$query = "UPDATE `tbl_order_tovar` SET `oprihodovan_vm`=-1 WHERE `id_tovar_order`=$id_tovar_order";
			if (!mysql_query($query)) echo "Update failed: $query<br>" .  mysql_error() . "<br><br>";
		}
		else echo "Нет привязки к VM";
	}
	
	function count_update()
	{
		////////////////////////////////////// Отрабатывваем кнопку Об-ть кол-во
		if (isset($_POST['u_count']) && isset($_POST['id_tovar_order']))
		{
			$id_tovar_order = get_post('id_tovar_order');
			$u_count = get_post('u_count');
			$query = "UPDATE tbl_order_tovar SET count='$u_count' WHERE id_tovar_order='$id_tovar_order'";
			//echo $query;
			if (!mysql_query($query))
				echo "Update failed: $query<br>" .	mysql_error() . "<br><br>";
		}

		else echo "Ошибка";
	}

    /**
     *Справочник товара из virtuemart в json формате. Подставляется в сombobox
     */
    function get_vm_tovar()
    {
        $sql="SELECT mszerg_autohome.v3t_virtuemart_products_ru_ru.virtuemart_product_id, mszerg_autohome.v3t_virtuemart_products_ru_ru.product_name FROM mszerg_autohome.v3t_virtuemart_products_ru_ru";
        $result = mysql_query($sql);
        //$json_data = mysql_fetch_array($result);
        $arr = array();
        while ( $json_data = mysql_fetch_assoc($result) ) $arr[] = $json_data;
        echo json_encode($arr);
    }

    /**
     *Обновление товара в таблице tbl_tovar при изменении в сombobox
     */
    function update_vm_tovar()
    {
        //$newValue = $this->get_post('newValue');
        $this->insert_vm_tovar();
        $query = "UPDATE tbl_tovar SET id_virtuemart = " . $this->get_post('newValue') . ", tbl_tovar.NameVirtuemart = \"" . $this->get_post('product_name')  . "\" WHERE (((tbl_tovar.id_import)=" . $this->get_post('ali_id_tovar') . "))";
        //echo $query;
		if (!mysql_query($query)) echo "Update failed: $query<br>" . mysql_error() . "<br><br>";

    }

    /**
     *Добавление товара в таблице tbl_tovar при изменении в сombobox
     */
    function insert_vm_tovar()
    {
        //$newValue = $this->get_post('newValue');
        $ali_id_tovar = $this->get_post('ali_id_tovar');
        $ali_name_tovar = $this->get_post('ali_name_tovar');
        $newValue = $this->get_post('newValue');
        $product_name = $this->get_post('product_name');
        $query = "INSERT INTO tbl_tovar (id_import, NameTovar , id_virtuemart ,NameVirtuemart) values ('$ali_id_tovar','$ali_name_tovar','$newValue','$product_name')";
        //echo $query;
        if (!mysql_query($query)) echo "Insert failed: $query<br>" . mysql_error() . "<br><br>";

    }
    /**
     *Обнуление товра в таблице tbl_tovar при нажатии крестика (отмена) в сombobox
     */
    function update_vm_tovar_null()
    {
         $query = "UPDATE tbl_tovar SET id_virtuemart = 0, tbl_tovar.NameVirtuemart = NULL WHERE (((tbl_tovar.id_import)=" . $this->get_post('ali_id_tovar') . "))";
        //echo $query;
        if (!mysql_query($query)) echo "Update failed: $query<br>" . mysql_error() . "<br><br>";

    }

    /**
     *Получение имени товар virtuemart по id
     *Подставляем в update_vm_tovar()
     */
   /* function get_vm_name_tovar($id_vm_tovar)
    {
        $sql="SELECT mszerg_autohome.v3t_virtuemart_products_ru_ru.product_name FROM mszerg_autohome.v3t_virtuemart_products_ru_ru WHERE mszerg_autohome.v3t_virtuemart_products_ru_ru.virtuemart_product_id=$id_vm_tovar";
        $result = mysql_query($sql);
        $row=mysql_fetch_assoc($result);

        return $row['product_name'];
    }*/



    private function get_post($var)
		{
			return mysql_real_escape_string($_POST[$var]);
		}

	
	///////////////////////////////////// Функциия для условия where в запросе на выборку
    private function addWhere($where, $add, $and = true)
    {
        if ($where) {
            if ($and) $where .= " AND $add";
            else $where .= " OR $add";
        } else $where = $add;
        return $where;
    }
	
	private function get_web_page($url,$id_ali)
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
            curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER['DOCUMENT_ROOT']."/cookies/Leonid/cookies.txt");
        } elseif ($id_ali == 2) {
            curl_setopt($ch, CURLOPT_COOKIEFILE,$_SERVER['DOCUMENT_ROOT']."/cookies/Dmitriy/cookies.txt");
        }

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
}
