<?php

namespace Jara\App\Controllers;

use Jara\App\Models\ShopModel;

class ShopController
{
    public $model;
    
    function __construct()
    {
        $this->model = new ShopModel();
    }
}
