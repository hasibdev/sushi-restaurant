<?php

namespace Jara\App\Controllers;

use Jara\App\Models\EmailModel;
use Jara\App\Views\EmailView;

class EmailController
{
    public $model;

    function __construct()
    {
        $this->model = new EmailModel();
        $this->view = new EmailView();
    }

    public function order_email($params)
    {
        $order_id = $params[0];
        if (!isset($params[1])) {
            $params[1] = true;
        }

        $order = $this->model->get_order($order_id);
        echo $this->view->order_email($order, $params[1]);
    }
}
