<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\OptionsModel;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;

class OptionsController
{
    public $model;

    function __construct()
    {
        $this->model = new OptionsModel();
        $this->api = Config::$app_url;
    }


    /**
     * Additions Controller
     */


    public function set_options()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        $array = $requst['object'];

        foreach ($array['list'] as $value) {
            if (isset($value['data']) && strlen($value['data']) > 5) {
                $url = Images::add($value['data'], 500, 500, 'webp');
                $value['url'] = $url;
                unset($value['data']);
            }
        }

        return $this->model->set_options($array);
    }

    public function get_options()
    {
        $results = $this->model->get_options();
        $response = [];

        foreach ($results as $result) {
            $schema = json_decode('{
                "id": 0,
                "name": "",
                "list": 0
            }');

            $schema->id = $result->id;
            $schema->name = $result->title;

            $items = json_decode($result->content);

            foreach ($items as $item) {
                if (isset($item->url)) {
                    $item->url = $this->api . '/uploads/images/' . $item->url;
                }
            }

            $schema->list = $items;

            array_push($response, $schema);
        }

        return json_encode($response);
    }

    public function get_option($id)
    {
        $result = $this->model->get_option($id);

        if (!$result) {
            return Response::new(404);
        }

        $schema = json_decode('{
                "name": "",
                "list": 0
            }');

        $schema->id = $result->id;
        $schema->name = $result->title;
        $schema->list = json_decode($result->content);

        return json_encode($schema);
    }

    public function update_options()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        $array = $requst['object'];

        foreach ($array['list'] as $value) {
            if (isset($value['data']) && strlen($value['data']) > 5) {
                if (isset($value['url']) && strlen($value['url']) > 5) {
                    Images::remove($value['url']);
                }
                $url = Images::add($value['data'], 500, 500, 'webp');
                $value['url'] = $url;
                unset($value['data']);
            }
        }

        print_r($array);

        return $this->model->update_options($array);
    }

    public function remove_options()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('id');

        $result = $this->model->get_option($requst['id']);

        $items = json_decode($result->content);

        foreach ($items as $item) {
            if (isset($item->url) && strlen($item->url) > 5) {
                Images::remove($item->url);
            }
        }

        return $this->model->remove_options($requst['id']);
    }
}
