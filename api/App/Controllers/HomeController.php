<?php

namespace Jara\App\Controllers;

use Jara\App\Models\HomeModel;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Session;

class HomeController
{
    public $model;

    function __construct()
    {
        $this->model = new HomeModel();
    }

    public function set_home()
    {
        Session::check([0, 1, 2, 3]);

        return $this->model->set_home();
    }

    public function get_home()
    {
        return $this->model->get_home();
    }
}
