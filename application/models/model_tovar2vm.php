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

    function parser_picture()
    {
        /*$snapshot_num=$this->get_post('snapshot_num');
        $namber_order=$this->get_post('namber_order');*/

        $url = 'http://g01.a.alicdn.com/kf/UT86qyGXDpaXXagOFbXs.jpg_50x50.jpg';
        $path = './images/ali-image/logo.jpg';
        file_put_contents($path, file_get_contents($url));
        $this->ftp_upload_picture("/public_html/logo66.jpg ", $path);


    }

    private function get_post($var)
    {
        return mysql_real_escape_string($_POST[$var]);
    }

    private function ftp_upload_picture($destination_file,$source_file)
    {
        require 'login.php';
        //$ftp_server="";
        //$ftp_user_name="";
        //$ftp_user_pass="";
        //$file = "";//tobe uploaded
        //$remote_file = "";

        // set up basic connection
       /* $conn_id = ftp_connect($ftp_server);
        echo $ftp_server;

        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        echo "</br>" . $login_result;
        // upload a file

        // включение пассивного режима
        ftp_pasv($conn_id, true);

        if (ftp_put($conn_id, $remote_file, $local_file, FTP_IMAGE)) {
            echo "successfully uploaded $local_file\n";
            //exit;
        } else {
            echo "There was a problem while uploading $local_file\n";
            //exit;
        }
        // close the connection
        echo ftp_close($conn_id);*/

        // установка соединения
        $conn_id = ftp_connect($ftp_server);

// вход с именем пользователя и паролем
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// проверка соединения
        if ((!$conn_id) || (!$login_result)) {
            echo "Не удалось установить соединение с FTP сервером!";
            echo "Попытка подключения к серверу $ftp_server под именем $ftp_user_name!";
            exit;
        } else {
            echo "Установлено соединение с FTP сервером $ftp_server под именем $ftp_user_name";
        }

// закачивание файла
        $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

// проверка результата
        if (!$upload) {
            echo "Не удалось закачать файл!";
        } else {
            echo "Файл $source_file закачен на $ftp_server под именем $destination_file";
        }

        if (ftp_chmod($conn_id, 0644, $destination_file) !== false) {
            echo "Права доступа к файлу $destination_file изменены на 644\n";
        } else {
            echo "Не удалось изменить права доступа к файлу $destination_file\n";
        }

// закрытие соединения
        ftp_close($conn_id);
    }


}
