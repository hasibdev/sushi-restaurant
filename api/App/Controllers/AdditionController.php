<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\AdditionModel;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class AdditionController
{
    public $model;

    function __construct()
    {
        $this->model = new AdditionModel();
        $this->api = Config::$app_url;
    }



    /**
     * Additions Controller
     */


    public function set_additions()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        if (isset($requst['object']['image']['data'])) {
            $url = Images::add($requst['object']['image']['data'], 500, 500, 'webp');
            $requst['object']['image']['url'] = $url;
            unset($requst['object']['image']['data']);
        }

        return $this->model->set_additions($requst['object']);
    }

    public function get_additions()
    {
        $results = $this->model->get_additions();
        $response = [];

        foreach ($results as $result) {
            $schema = json_decode('{
                "name": "",
                "price": 0,
                "image": {
                    "url": ""
                }
            }');

            $schema->id = $result->id;
            $schema->name = $result->title;
            $schema->price = $result->content;
            $img = json_decode($result->description);
            if (isset($img)) {
                $schema->image->url = $this->api . '/uploads/images/' . $img->url;
            }

            array_push($response, $schema);
        }

        return json_encode($response);
    }

    public function get_addition($id)
    {
        $result = $this->model->get_addition($id);

        if (!$result) {
            return Response::new(404);
        }

        $schema = json_decode('{
                "name": "",
                "price": 0
            }');

        // add image url
        if (isset($result->image)) {
            $result->image = $this->api . $result->image;
        }

        $schema->id = $result->id;
        $schema->name = $result->title;
        $schema->price = $result->content;

        return json_encode($schema);
    }

    public function update_additions()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        // remove old and update new image
        if (isset($requst['object']['image']['data'])) {
            // remove old image
            if (isset($requst['object']['image']['url'])) {
                Images::remove($requst['object']['image']['url']);
            }
            $url = Images::add($requst['object']['image']['data'], 500, 500, 'webp');
            $requst['object']['image']['url'] = $url;
            unset($requst['object']['image']['data']);
        }

        return $this->model->update_additions($requst['object']);
    }

    public function remove_additions()
    {
        Session::check([0, 1, 2]);

        $requst = Request::require('id');

        // remove old image
        if (isset($requst['id'])) {
            $result = $this->model->get_addition($requst['id']);
            $img = json_decode($result->description);
            if (isset($img->url)) {
                Images::remove($img->url);
            }
        }

        return $this->model->remove_additions($requst['id']);
    }
}
