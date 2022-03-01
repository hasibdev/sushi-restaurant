<?php

namespace Jara\App\Controllers;

use Jara\App\Models\ContactModel;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class ContactController
{
    public $model;

    function __construct()
    {
        $this->model = new ContactModel();
    }

    public function set_contact()
    {
        Session::check([0, 1, 2, 3]);
        $request = Request::require('object');
        $result = $this->model->set_contact($request['object']);

        return Response::new(200, $result);
    }

    public function get_contact()
    {
        $result = json_decode($this->model->get_contact());
        $loc_id = $result->locations;
        $result->sections = [];
        $reverse = false;

        foreach ($loc_id as $id) {
            $location_data = $this->model->get_location($id);
            $location_data->content = json_decode($location_data->content);
            if ($location_data) {
                $schema = new \stdClass();
                $schema->name = "Location";
                $schema->props = new \stdClass();
                $schema->props->location = [];
                $location_data->content->id = $location_data->id;
                $schema->props->location = $location_data->content;
                $schema->props->reverse = !$reverse;
                $reverse = !$reverse;
                $result->sections[] = $schema;
            }
        }


        unset($result->locations);

        return json_encode($result);
    }
}
