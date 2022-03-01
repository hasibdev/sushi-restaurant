<?php

namespace Jara\App\Views;

use Jara\App\Controllers\HomeController;

class HomeView
{
    public $controller;

    function __construct()
    {
        $this->controller = new HomeController();
    }

    public function set_home()
    {
        echo  $this->controller->set_home();
    }

    public function get_home()
    {
        echo  $this->controller->get_home();
    }
}
