<?php

namespace Jara\App\Controllers;

use Jara\App\Models\BlogModel;

class BlogController
{
    public $model;

    function __construct()
    {
        $this->model = new BlogModel();
    }
}
