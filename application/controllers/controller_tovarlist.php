<?php

class Controller_Tovarlist extends Controller
{

	function __construct()
	{
		$this->model = new Model_Tovarlist();
		$this->view = new View();
	}
	
	function action_index()
	{
		///////////////////////////////////////////Запоминаем данные в сессию
		session_start();
        if (isset($_POST['AllRecord']))     $_SESSION['filtr_AllRecord'] = $_POST['AllRecord'];
        if (isset($_POST['find_tovar']))    $_SESSION['filtr_tovar'] = $_POST['find_tovar'];
		//if (empty($_POST["refresh_count"])) $_SESSION['filtr_order'] = fOrder($_POST["find_order"],$_POST["namber_order"]);
		if (isset($_POST["find_order"]))    $_SESSION['filtr_order'] = $_POST["find_order"];
        if (isset($_POST['begin_date']))    $_SESSION['filtr_begin_date'] = $_POST["begin_date"];
        if (isset($_POST['end_date']))      $_SESSION['filtr_end_date'] = $_POST["end_date"];

		/////////////////////////////////////////// Обнуляем сессию если нажата кнопка Удалить фильтр
		if (isset($_POST["delfilter"])) {
		$_SESSION['filtr_AllRecord']="";
		$_SESSION['filtr_tovar'] = "";
		$_SESSION['filtr_order'] = "";
		$_SESSION['filtr_begin_date'] = "";
		$_SESSION['filtr_end_date'] = "";
		}

		//////////////////////////////////////////Присваиваем перемменным значения сессии
        (isset($_SESSION["filtr_AllRecord"])    ? $filtr_AllRecord = htmlspecialchars($_SESSION['filtr_AllRecord']) : $filtr_AllRecord = 1); //по умолчанию выводим первые 100 записей, что бы не нагружать запрос
        (isset($_SESSION["filtr_tovar"])        ?  $filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']) : $filtr_tovar="");
        if (isset($_SESSION["filtr_order"]))        $filtr_order = htmlspecialchars($_SESSION['filtr_order']);
        if (isset($_SESSION["filtr_begin_date"]))   $filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']);
        if (isset($_SESSION["filtr_end_date"]))     $filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']);

        // Главный запрос на выборку без Where и Order by
        $sql  = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*, tbl_tovar.id_virtuemart, tbl_tovar.NameVirtuemart
		FROM ((tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali) INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order) LEFT JOIN tbl_tovar ON tbl_order_tovar.ali_id_tovar = tbl_tovar.id_import";
        echo "Фильтр по товару: " . $filtr_tovar . "</br>";
        //Добавляем условия к основному запросу в при нажатии разных кнопок
        if (!empty($_POST["filter"]) or !empty($_POST["filtr_by_order"]) or !empty($_POST["u_count"]) or !empty($_POST["refresh_status"]) or !empty($_POST['vm_update_count'])) {
            $where = "";
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
        //echo $sql;
	
		$data = $this->model->get_data($sql);
        //var_dump($data);
		$this->view->generate('tovarlist_view.php', 'template_view.php', $data);
	}


    function action_load_status()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/library/simple_html_dom.php';
		
		$namber_order = $_POST['find_order'];
		$id_ali = $_POST['id_ali'];
		
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
                /*echo $query;
                if (!mysql_query($query, $db_server))
                    echo "Update failed: $query<br>" . mysql_error() . "<br><br>";*/

                $this->model->update_data($query);

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
                    /*if (!mysql_query($query, $db_server))
                        echo "Update failed: $query<br>" . mysql_error() . "<br><br>";*/
                    $this->model->update_data($query);
                }
                echo "Удачно, об-те стр-цу";
            }
            else
            {
                echo "Ошибка, обновите куки";
            }
        }
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

    ///////////////////////////////////// Функциия для условия where в запросе на выборку
    private function addWhere($where, $add, $and = true)
    {
        if ($where) {
            if ($and) $where .= " AND $add";
            else $where .= " OR $add";
        } else $where = $add;
        return $where;
    }
}
