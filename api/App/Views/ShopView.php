<?php

namespace Jara\App\Views;

use Jara\App\Controllers\ShopController;

class ShopView
{
    public $controller;
    
    function __construct()
    {
        $this->controller = new ShopController();
    }
}