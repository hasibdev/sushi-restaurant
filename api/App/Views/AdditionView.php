<?php

namespace Jara\App\Views;

use Jara\App\Controllers\AdditionController;

class AdditionView
{
    public $controller;

    function __construct()
    {
        $this->controller = new AdditionController();
    }


    /**
     * Addition view
     */

    public function get_additions()
    {
        echo $this->controller->get_additions();
    }

    public function get_addition($params)
    {
        echo $this->controller->get_addition($params[0]);
    }

    public function set_additions()
    {
        echo $this->controller->set_additions();
    }

    public function update_additions()
    {
        echo $this->controller->update_additions();
    }

    public function remove_additions()
    {
        echo $this->controller->remove_additions();
    }
}
