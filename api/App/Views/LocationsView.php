<?php

namespace Jara\App\Views;

use Jara\App\Controllers\LocationsController;

class LocationsView
{
    public $controller;

    function __construct()
    {
        $this->controller = new LocationsController();
    }

    public function get_location($id)
    {
        echo $this->controller->get_location($id[0]);
    }

    public function get_locations()
    {
        echo $this->controller->get_locations();
    }

    public function set_location()
    {
        echo $this->controller->set_location();
    }

    public function update_location()
    {
        echo $this->controller->update_location();
    }

    public function delete_location()
    {
        echo $this->controller->delete_location();
    }
}
