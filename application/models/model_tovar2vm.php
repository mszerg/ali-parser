<?php
/**
 * Created by PhpStorm.
 * User: mszerg
 * Date: 02.05.16
 * Time: 18:28
 */

class Model_Tovar2vm extends Model
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

    /**
     *Получение имени товар virtuemart по id
     *Подставляем в update_vm_tovar()
     */
    function get_data($page)
    {
        $count = 1;// Количество записей на странице
        //$page = $_GET["page"];// Узнаём номер страницы
        $shift = $count * ($page - 1);// Смещение в LIMIT. Те записи, порядковый номер которого больше этого числа, будут выводиться.
        //$sql = "SELECT * FROM `tbl_order_tovar` LIMIT $shift, $count";// Делаем выборку $count записей, начиная с $shift + 1.
        $sql = "SELECT tbl_order_user.*, tbl_order.*, tbl_order_tovar.*, tbl_tovar.id_virtuemart, tbl_tovar.NameVirtuemart, mszerg_autohome.v3t_virtuemart_products_ru_ru.*
		FROM ((tbl_order INNER JOIN tbl_order_user ON tbl_order.id_ali = tbl_order_user.id_ali)
		INNER JOIN tbl_order_tovar ON tbl_order.namber_order = tbl_order_tovar.namber_order)
		LEFT JOIN tbl_tovar ON tbl_order_tovar.ali_id_tovar = tbl_tovar.id_import
		LEFT JOIN mszerg_autohome.v3t_virtuemart_products_ru_ru ON tbl_tovar.id_virtuemart = mszerg_autohome.v3t_virtuemart_products_ru_ru.virtuemart_product_id
		order by tbl_order.date_order desc LIMIT $shift, $count";

        //$sql="SELECT mszerg_autohome.v3t_virtuemart_products_ru_ru.product_name FROM mszerg_autohome.v3t_virtuemart_products_ru_ru WHERE mszerg_autohome.v3t_virtuemart_products_ru_ru.virtuemart_product_id=$id_vm_tovar";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);

        return $row;
    }

    function add_rec()
    {
        $product_s_desc = $this->get_post('product_s_desc');
        $product_name = $this->get_post('product_name');
        $metadesc = $this->get_post('metadesc');
        $metakey = $this->get_post('metakey');
        $customtitle = $this->get_post('customtitle');
        $slug = $this->get_post('slug');

        $product_available_date = date("Y-m-d");
        $created_on = $modified_on = date("Y-m-d H:i:s");

        //$query = "UPDATE tbl_order_tovar SET count='$u_count' WHERE id_tovar_order='$id_tovar_order'";

        $query  = "INSERT INTO mszerg_autohome.v3t_virtuemart_products(product_weight_uom, product_lwh_uom, product_available_date, product_unit, product_params, metaauthor, published, created_on, created_by, modified_on, modified_by)
        VALUES ('KG','M','$product_available_date','KG','min_order_level=\"\"|max_order_level=\"\"|step_order_level=\"\"|product_box=\"\"|','mszerg','0','$created_on','31','$modified_on','31')";

        /*$query= "BEGIN;
                    INSERT INTO mszerg_autohome.v3t_virtuemart_products(product_weight_uom, product_lwh_uom, product_available_date, product_unit, product_params, metaauthor, published, created_on, created_by, modified_on, modified_by)
                        VALUES ('KG','M','$product_available_date','KG','min_order_level=\"\"|max_order_level=\"\"|step_order_level=\"\"|product_box=\"\"|','mszerg','0','$created_on','31','$modified_on','31');
                    INSERT INTO mszerg_autohome.v3t_virtuemart_products_ru_ru(virtuemart_product_id, product_s_desc, product_name, metadesc, metakey, customtitle, slug)
                        VALUES (LAST_INSERT_ID(),'$product_s_desc','$product_name','$metadesc','$metakey','$customtitle','$slug')
                COMMIT;";*/


        /*$query = "INSERT INTO mszerg_autohome.v3t_virtuemart_products_ru_ru(product_s_desc, product_name, metadesc, metakey, customtitle, slug)
        VALUES ('$product_s_desc','$product_name','$metadesc','$metakey','$customtitle','$slug')";*/
        //echo $query;

        if (!mysql_query($query))
            echo "Update failed: $query<br>" . mysql_error() . "<br><br>";

        $user_id= mysql_insert_id();
        if(!empty($user_id)) {

            $sql = "INSERT INTO mszerg_autohome.v3t_virtuemart_products_ru_ru(virtuemart_product_id, product_s_desc, product_name, metadesc, metakey, customtitle, slug)
                    VALUES ('$user_id', '$product_s_desc','$product_name','$metadesc','$metakey','$customtitle','$slug')";

            if (!mysql_query($sql))
                echo "Update failed: $query<br>" . mysql_error() . "<br><br>";
        };
    }

    function parser_picture($ali_id_tovar,$namber_order,$id_ali)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/library/simple_html_dom.php';

        //echo "namber_order = " . $namber_order . " id = " . $id_ali;

        $result = $this->get_web_page("http://www.aliexpress.com/snapshot/$ali_id_tovar.html?orderId=$namber_order",$id_ali);
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

                $str_tracknumber= trim($html->find('td[class=no]', 0)->plaintext);

                $query = "UPDATE tbl_order SET status='$str_status', tracknumber='$str_tracknumber' WHERE namber_order=$namber_order";
                //echo $query;
                if (!mysql_query($query))
                    echo "Update failed: $query<br>" . mysql_error() . "<br><br>";

                //$this->model->update_data($query);

                $html = $html->find('.product-table',0);
                //echo $html;

                $url = 'http://img.yandex.net/i/www/logo.png';
                $path = './images/logo.png';
                file_put_contents($path, file_get_contents($url));

                echo "$namber_order - Удачно, об-те стр-цу" . "<br>";
            }
            else
            {
                echo "$namber_order - Ошибка, обновите куки" . "<br>";
            }
        }
    }

    private function get_post($var)
    {
        return mysql_real_escape_string($_POST[$var]);
    }

}