<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\OfferModel;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;


class OfferController
{
    public $model;

    function __construct()
    {
        $this->model = new OfferModel();
        $this->api = Config::$app_url;
    }


    /**
     * Offers Controller
     */

    public function set_offers()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->set_offers($requst['object']);
    }

    public function get_offers()
    {
        $results = $this->model->get_offers();
        $response = [];

        foreach ($results as $result) {

            $schema = json_decode(' {
                "id": 0,
                "name": "",
                "description": "",
                "type": "",
                "discountValue": 0,
                "freeProductId": 0,
                "conditions": [],
                "image": {
                    "name": "",
                    "data": ""
                }
            }');

            if (strtoupper($result->content) === "DISCOUNT") {
                unset($schema->freeProductId);
                $schema->discountValue = floatval(Posts::get_meta($result->id, 'discount_value')->value);
            } else {
                unset($schema->discountValue);
                $schema->freeProductId = intval(Posts::get_meta($result->id, 'free_product_id')->value);
            }

            $image = json_decode(Posts::get_meta($result->id, 'image')->value);

            if (isset($image->url)) {
                $image->url = $this->api . '/uploads/images/' . $image->url;
            }

            $options = json_decode(Posts::get_meta($result->id, 'conditions')->value);

            foreach ($options as $id => $value) {
                $options[$id]->id = $id + 1;
            }


            $schema->id = intval($result->id);
            $schema->name = $result->title;
            $schema->description = $result->description;
            $schema->type = $result->content;
            $schema->image = $image;
            $schema->conditions = $options;

            array_push($response, $schema);
        }

        return json_encode($response);
    }

    public function get_offer($id)
    {
        $result = $this->model->get_offer($id);

        if (!$result) {
            return Response::new(404);
        }

        $schema = json_decode(' {
            "id": 0,
            "name": "",
            "description": "",
            "type": "",
            "discountValue": 0,
            "freeProductId": 0,
            "conditions": [],
            "image": {
                "name": "",
                "data": ""
            }
        }');

        if (strtoupper($result->content) === "DISCOUNT") {
            unset($schema->freeProductId);
            $schema->discountValue = floatval(Posts::get_meta($result->id, 'discount_value')->value);
        } else {
            unset($schema->discountValue);
            $schema->freeProductId = intval(Posts::get_meta($result->id, 'free_product_id')->value);
        }

        $image = json_decode(Posts::get_meta($result->id, 'image')->value);

        if (isset($image->url)) {
            $image->url = $this->api . '/uploads/images/' . $image->url;
        }

        $options = json_decode(Posts::get_meta($result->id, 'conditions')->value);

        foreach ($options as $id => $value) {
            $options[$id]->id = $id + 1;
        }


        $schema->id = intval($result->id);
        $schema->name = $result->title;
        $schema->description = $result->description;
        $schema->type = $result->content;
        $schema->image = $image;
        $schema->conditions = $options;

        return Response::new(200, $schema);
    }

    public function update_offers()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('object');

        return $this->model->update_offers($requst['object']);
    }

    public function remove_offers()
    {
        Session::check([0, 1, 2, 3]);

        $requst = Request::require('id');

        return $this->model->remove_offers($requst['id']);
    }
}
