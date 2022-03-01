<?php

namespace Jara\App\Views;

use Jara\App\Controllers\OptionsController;

class OptionsView
{
    public $controller;

    function __construct()
    {
        $this->controller = new OptionsController();
    }

    /**
     * Addition view
     */

    public function get_options()
    {
        echo $this->controller->get_options();
    }

    public function get_option($params)
    {
        echo $this->controller->get_option($params[0]);
    }

    public function set_options()
    {
        echo $this->controller->set_options();
    }

    public function update_options()
    {
        echo $this->controller->update_options();
    }

    public function remove_options()
    {
        echo $this->controller->remove_options();
    }
}
