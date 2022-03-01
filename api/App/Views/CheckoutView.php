<?php

namespace Jara\App\Views;

use Jara\App\Controllers\CheckoutController;

class CheckoutView
{
    public $controller;

    function __construct()
    {
        $this->controller = new CheckoutController();
    }

    public function set_order()
    {
        echo $this->controller->set_order();
    }

    public function get_order($id)
    {
        echo $this->controller->get_order($id);
    }

    public function get_orders($page)
    {
        echo $this->controller->get_orders($page);
    }

    public function get_user_orders($page)
    {
        echo $this->controller->get_user_orders($page);
    }

    public function get_pending_orders($page)
    {
        echo $this->controller->get_pending_orders($page);
    }

    public function update_status()
    {
        echo $this->controller->update_status();
    }
}
