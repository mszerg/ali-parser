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
		$data = $this->model->get_data();
        //var_dump($data);
		$this->view->generate('tovarlist_view.php', 'template_view.php', $data);
	}

	function action_update_all_status()
	{
		$sql = "SELECT tbl_order.id_ali, tbl_order.namber_order FROM tbl_order WHERE (((tbl_order.status)<>'Завершено' And (tbl_order.status)<>'Закрыт'));";
		$data = $this->model->get_data($sql);
		foreach($data as $row)
		{
			$result = $this->model->load_status_params($row['namber_order'],$row['id_ali']);
			if ($result = "Ошибка, обновите куки") 
			{
				//echo "namber_order=" . $row['namber_order'] . " id_ali=" . $row['id_ali'];
				//exit;
			}
		}
	}

    function action_load_status()
    {
		$this->model->load_status_params($_POST['find_order'],$_POST['id_ali']);
    }

	function action_vm_count_update() //ПРрибавляем к остатку в VM и ставим галочку, что товар оприходоват
    {
		$this->model->vm_count_update();
    }
	
	function action_count_update() //обновляем количество товара при нажатии кнопки
    {
		$this->model->count_update();
    }

}
