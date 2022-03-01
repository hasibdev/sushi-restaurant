<?php

namespace Jara\App\Views;

use Jara\App\Controllers\DiscountController;

class DiscountView
{
    public $controller;

    function __construct()
    {
        $this->controller = new DiscountController();
    }

    /**
     * Discount view
     */

    public function get_discounts()
    {
        echo $this->controller->get_discounts();
    }

    public function get_discount($params)
    {
        echo $this->controller->get_discount($params[0]);
    }

    public function set_discounts()
    {
        echo $this->controller->set_discounts();
    }

    public function update_discounts()
    {
        echo $this->controller->update_discounts();
    }

    public function remove_discounts()
    {
        echo $this->controller->remove_discounts();
    }
}
