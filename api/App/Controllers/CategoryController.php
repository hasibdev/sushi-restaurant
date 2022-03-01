<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\CategoryModel;
use Jara\Core\Helpers\Categories;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class CategoryController
{
    public $model;

    function __construct()
    {
        $this->model = new CategoryModel();
        $this->api = Config::$app_url;
    }



    /**
     * Category Controller
     */


    public function set_category()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->set_category($requst['object']);
    }

    public function get_category()
    {
        $results = $this->model->get_category();
        $response = [];

        foreach ($results as $result) {
            $schema = json_decode('{
                "name": "",
                "order": "",
                "image": {
                    "title": "",
                    "url": ""
                }
            }');

            $image = json_decode($result->description);

            if (isset($image->url)) {
                $image->url = $this->api . '/uploads/images/' . $image->url;
            }

            $order = Categories::get_meta($result->id, 'order');

            if ($order) {
                $schema->order = intval($order->value);
            } else {
                $schema->order = 100;
            }


            $schema->id = $result->id;
            $schema->name = $result->name;
            $schema->image = $image;



            array_push($response, $schema);
        }



        usort($response, function ($a, $b) {
            if ($a->order == $b->order) {
                return 0;
            }
            return ($a->order < $b->order) ? -1 : 1;
        });

        return json_encode($response);
    }

    public function get_single_category($id)
    {
        $result = $this->model->get_single_category($id);

        if (!$result) {
            return Response::new(404);
        }

        $schema = json_decode('{
                "name": "",
                "order": "",
                "image": {
                    "title": "",
                    "url": ""
                }
            }');


        $image = json_decode($result->description);

        if (isset($image->url)) {
            $image->url = $this->api . '/uploads/images/' . $image->url;
        }

        $schema->id = $result->id;
        $schema->name = $result->name;
        $schema->image = $image;

        $options = json_decode(Categories::get_meta($result->id, 'options')->value) ?? [];

        $schema->options = $options;


        $order = Categories::get_meta($result->id, 'order');

        if ($order) {
            $schema->order = intval($order->value);
        } else {
            $schema->order = 100;
        }

        return json_encode($schema);
    }

    public function update_category()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->update_category($requst['object']);
    }

    public function remove_category()
    {
        Session::check([0, 1, 2]);

        $requst = Request::require('id');

        $products = $this->model->get_products_by_cat($requst['id']);

        if ($products) {
            return Response::new(400, 'Category has products you must remove or change their category first');
        }

        return $this->model->remove_category($requst['id']);
    }
}
