<?php

class Model_Tovarlist extends Model
{
	
	public function get_data($sql)
	{
		//в массиве $data передаем данные из _POST контроллера Controller_Tovarlist
		//
		//
		//
		/*if(is_array($data)) {
			// преобразуем элементы массива в переменные
			extract($data);
		}*/
	
		///////////////////////////////// BEGIN Podkluchaem BD //////////////////////////////
		require_once 'login.php';
		$db_server = mysql_connect($db_hostname, $db_username, $db_password);

		if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

		mysql_select_db($db_database, $db_server)
		or die("Unable to select database: " . mysql_error());

		mysql_query("SET NAMES utf8");

        $result = mysql_query($sql);
        $arr = array();
        echo $sql . "</br>". "</br>";

        while ( $row = mysql_fetch_array($result) ) $arr[] = $row;
        //$rows = mysql_fetch_assoc($result);
        //$result = mysql_query("SELECT * FROM table");


        return $arr;


    }

    public function update_data($sql)
    {
        require 'login.php';
		//echo "db_hostname = " . $db_hostname . " db_username = " . $db_username . " db_password = " . $db_password . "</br>";
        $db_server = mysql_connect($db_hostname, $db_username, $db_password);

        if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

        mysql_select_db($db_database, $db_server)
        or die("Unable to select database: " . mysql_error());

        mysql_query("SET NAMES utf8");

        //echo $sql . "</br>". "</br>";

        if (mysql_query($sql, $db_server))
        {
            return true;
        }
        else
        {
            return false;
            //echo "Update failed: $query<br>" . mysql_error() . "<br><br>";
        }


    }
}
