<?php

namespace Jara\App\Controllers;

use Jara\App\Models\PageModel;

class PageController
{
    public $model;
    
    function __construct()
    {
        $this->model = new PageModel();
    }
}
