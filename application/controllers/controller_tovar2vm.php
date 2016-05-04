<?php
/**
 * Created by PhpStorm.
 * User: mszerg
 * Date: 02.05.16
 * Time: 18:29
 */


class Controller_Tovar2vm extends Controller
{
    function __construct()
    {
        $this->model = new Model_Tovar2vm();
        $this->view = new View();
    }

    function action_index()
    {
        parse_str($_SERVER['QUERY_STRING'], $queryVars ); //   &$queryVars

        // Убиваем нашу GET-переменную
        /*if( isset($queryVars[$varName]) ) {
            unset( $queryVars[$varName] );
        }*/

        $page=$queryVars['start']+1;
        $data = $this->model->get_data($page);

        //var_dump($data);
        $this->view->generate('tovar2vm_view.php', 'template_view.php', $data);
    }

    function action_add_rec()
    {
        $this->model->add_rec();
    }

}