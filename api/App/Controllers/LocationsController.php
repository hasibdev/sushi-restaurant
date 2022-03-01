<?php

namespace Jara\App\Controllers;

use Jara\App\Models\LocationsModel;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class LocationsController
{
    public $model;

    function __construct()
    {
        $this->model = new LocationsModel();
    }

    public function get_location($id)
    {
        $data = $this->model->get_location($id);
        $data->content = json_decode($data->content);
        return Response::new(200, $data);
    }

    public function get_locations()
    {
        return json_encode($this->model->get_locations());
    }

    public function set_location()
    {
        Session::check([0, 1, 2, 3]);
        $request = Request::require('object');
        return $this->model->set_location($request['object']);
    }

    public function update_location()
    {
        Session::check([0, 1, 2, 3]);
        $request = Request::require('object');
        return $this->model->update_location($request['id'], $request['object']);
    }

    public function delete_location()
    {
        Session::check([0, 1, 2, 3]);
        $request = Request::require('id');
        return $this->model->delete_location($request['id']);
    }
}
