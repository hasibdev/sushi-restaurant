<?php

namespace Jara\App\Controllers;

use Jara\App\Models\ThemeModel;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class ThemeController
{
    public $model;

    function __construct()
    {
        $this->model = new ThemeModel();
    }

    public function set_themes()
    {
        Session::check([0, 1, 2]);

        $request = Request::require('object');

        $this->model->set_themes($request['object']);

        return Response::new(200);
    }

    public function get_themes()
    {
        return $this->model->get_themes();
    }
}
