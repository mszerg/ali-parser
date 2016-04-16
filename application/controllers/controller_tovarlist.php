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
		//session_start();
		$_SESSION['filtr_AllRecord'] = $_POST["AllRecord"];
		$_SESSION['filtr_tovar'] = $_POST["find_tovar"];
		//if (empty($_POST["refresh_count"])) $_SESSION['filtr_order'] = fOrder($_POST["find_order"],$_POST["namber_order"]);
		if (empty($_POST["refresh_count"])) $_SESSION['filtr_order'] = $_POST["find_order"];
		$_SESSION['filtr_begin_date'] = $_POST["begin_date"];
		$_SESSION['filtr_end_date'] = $_POST["end_date"];

		/////////////////////////////////////////// Обнуляем сессию если нажата кнопка Удалить фильтр
		if (!empty($_POST["delfilter"])) {
		$_SESSION['filtr_AllRecord']="";
		$_SESSION['filtr_tovar'] = "";
		$_SESSION['filtr_order'] = "";
		$_SESSION['filtr_begin_date'] = "";
		$_SESSION['filtr_end_date'] = "";
		}

		//////////////////////////////////////////Присваиваем перемменным значения сессии
		$filtr_AllRecord = htmlspecialchars($_SESSION['filtr_AllRecord']);
		$filtr_tovar = htmlspecialchars($_SESSION['filtr_tovar']);
		$filtr_order = htmlspecialchars($_SESSION['filtr_order']);
		$filtr_begin_date = htmlspecialchars($_SESSION['filtr_begin_date']);
		$filtr_end_date = htmlspecialchars($_SESSION['filtr_end_date']);
		
		
			
	
		$data = $this->model->get_data();		
		$this->view->generate('tovarlist_view.php', 'template_view.php', $data);
	}
}
