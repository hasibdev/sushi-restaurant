<?php

namespace Jara\App\Views;

use Jara\App\Controllers\DashboardController;

class DashboardView
{
    public $controller;

    function __construct()
    {
        $this->controller = new DashboardController();
    }

    public function get_overview($year)
    {
        echo $this->controller->get_overview($year[0]);
    }

    public function get_report()
    {
        echo $this->controller->get_report();
    }
}
