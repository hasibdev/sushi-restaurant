<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\MenuModel;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class MenuController
{
    public $model;

    function __construct()
    {
        $this->model = new MenuModel();
        $this->api = Config::$app_url;
    }


    /**
     * Products Controller
     */

    public function set_items()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->set_items($requst['object']);
    }

    public function get_items()
    {
        $results = $this->model->get_items();
        $response = [];

        foreach ($results as $result) {
            $schema = json_decode('{
              "categoryId": 0,
              "name": "",
              "image": {
                "title": "",
                "url": ""
              },
              "description": "",
              "price": 0,
              "options": [],
              "additions": []
            }');

            $image = json_decode(Posts::get_meta($result->id, 'image')->value);

            if (isset($image->url)) {
                $image->url = $this->api . '/uploads/images/' . $image->url;
            }

            $additions = json_decode(Posts::get_meta($result->id, 'additions')->value);
            $new_additions = [];
            foreach ($additions as $key => $addition) {
                $addition_result = $this->model->get_addition($addition);

                if ($addition_result) {
                    $add = ['name' => $addition_result->title, 'price' => floatval($addition_result->content), 'id' => intval($addition_result->id)];

                    array_push($new_additions, $add);
                }
            }


            $options = json_decode(Posts::get_meta($result->id, 'options')->value);
            $new_options = [];
            foreach ($options as $key => $option) {
                $option_result = $this->model->get_option($option);

                if (!is_string($option_result)) {
                    foreach ($option_result->list as $id => $value) {
                        $option_result->list[$id]->price = floatval($option_result->list[$id]->price);
                        $option_result->list[$id]->id = $id + 1;
                    }
                    array_push($new_options, $option_result);
                }
            }

            $schema->id = intval($result->id);
            $schema->name = $result->title;
            $schema->description = $result->description;
            $schema->price = floatval($result->content);
            $schema->image = $image;
            $schema->order = intval(Posts::get_meta($result->id, 'order')->value ?? 100);
            $schema->options = $new_options;
            $schema->additions = $new_additions;
            $schema->categoryId = Posts::get_meta($result->id, 'category_id')->value;

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

    public function get_item($id)
    {
        $result = $this->model->get_item($id);

        if (!$result) {
            return Response::new(404);
        }

        $schema = json_decode('{
              "categoryId": 0,
              "name": "",
              "image": {
                "title": "",
                "url": ""
              },
              "description": "",
              "price": 0,
              "options": [],
              "additions": []
            }');


        $image = json_decode(Posts::get_meta($result->id, 'image')->value);

        if (isset($image->url)) {
            $image->url = $this->api . '/uploads/images/' . $image->url;
        }

        $additions = json_decode(Posts::get_meta($result->id, 'additions')->value);
        $new_additions = [];
        foreach ($additions as $key => $addition) {
            $addition_result = $this->model->get_addition($addition);
            if ($addition_result) {
                $add = ['name' => $addition_result->title, 'price' => floatval($addition_result->content), 'id' => intval($addition_result->id)];
                array_push($new_additions, $add);
            }
        }


        $options = json_decode(Posts::get_meta($result->id, 'options')->value);
        $new_options = [];
        foreach ($options as $key => $option) {
            $option_result = $this->model->get_option($option);

            if (!is_string($option_result)) {
                foreach ($option_result->list as $id => $value) {
                    $option_result->list[$id]->price = floatval($option_result->list[$id]->price);
                    $option_result->list[$id]->id = $id + 1;
                }

                array_push($new_options, $option_result);
            }
        }

        $schema->id = intval($result->id);
        $schema->name = $result->title;
        $schema->description = $result->description;
        $schema->price = floatval($result->content);
        $schema->order = intval(Posts::get_meta($result->id, 'order')->value ?? 100);
        $schema->image = $image;
        $schema->options = $new_options;
        $schema->additions = $new_additions;
        $schema->categoryId = Posts::get_meta($result->id, 'category_id')->value;

        return Response::new(200, $schema);
    }

    public function update_items()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->update_items($requst['object']);
    }

    public function remove_items()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('id');

        return $this->model->remove_items($requst['id']);
    }
}
