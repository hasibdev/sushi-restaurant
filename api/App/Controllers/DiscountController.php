<?php

namespace Jara\App\Controllers;

use Jara\App\Models\DiscountModel;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;
use stdClass;

class DiscountController
{
    public $model;

    function __construct()
    {
        $this->model = new DiscountModel();
    }

    /**
     * Additions Controller
     */


    public function set_discounts()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->set_discounts($requst['object']);
    }

    public function get_discounts()
    {
        Session::check([0, 1, 2, 3, 4]);

        $results = $this->model->get_discounts();
        $response = [];
        foreach ($results as $result) {

            $schema = new stdClass();

            $schema->id = $result->id;
            $schema->name = $result->title;
            $schema->code = $result->description;
            $schema->value = floatval($result->content);
            $schema->expire_date = $result->slug;

            array_push($response, $schema);
        }

        return json_encode($response);
    }

    public function get_discount($id)
    {
        $result = $this->model->get_discount($id);

        if (!$result) {
            return Response::new(404);
        }

        $schema = new stdClass();

        $schema->id = $result->id;
        $schema->name = $result->title;
        $schema->code = $result->description;
        $schema->value = floatval($result->content);
        $schema->expire_date = $result->slug;

        return json_encode($schema);
    }

    public function update_discounts()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->update_discounts($requst['object']);
    }

    public function remove_discounts()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('id');

        return $this->model->remove_discounts($requst['id']);
    }
}
