<?php

namespace Jara\App\Controllers;

use Jara\App\Models\SettingsModel;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class SettingsController
{
    public $model;

    function __construct()
    {
        $this->model = new SettingsModel();
    }

    public function set_settings()
    {
        Session::check([0, 1, 2]);

        $request = Request::require('object');

        return Response::new(200, $this->model->set_settings($request['object']));
    }

    public function get_settings()
    {
        $result = $this->model->get_settings();

        return $result;
    }
}
