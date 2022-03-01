<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\PostModel;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Posts;

class PostController
{
    public $model;

    function __construct()
    {
        $this->model = new PostModel();
    }

    public function get_all_posts()
    {
        Session::check();

        if (!isset($_GET['type']) || !in_array(strtolower($_GET['type']), Config::$post_types)) {
            echo Response::new(400);
            exit();
        }

        $result = $this->model->get_all_posts($_GET['type']);

        return Response::new(200, $result);
    }

    public function add_post()
    {
        Session::check([0, 1, 2, 3, 4]);

        $post_type = Request::require('post_type');


        if (strtolower($post_type['post_type']) === 'products') {

            $request = Request::require(
                'name',
                'desc',
                'content',
                'post_type',
                'comments',
                'visible',
                'mime_type',
                'slug',
                'category',
                'brand',
                'quantity_box',
                'weight_item',
                'price',
                'price_box',
                'cost_price'
            );

            $id = $this->model->add_post(
                Essentials::id(),
                $request['name'],
                $request['desc'],
                $request['content'],
                $request['post_type'],
                $request['comments'],
                $request['visible'],
                $request['mime_type'],
                $request['slug']
            );

            if (strlen($request['image'])) {
                $path = Images::add(
                    $request['image'],
                    300,
                    300,
                    'webp'
                );
            } else {
                $path = '';
            }

            Posts::set_meta($id, 'image', $path);
            Posts::set_meta($id, 'category', $request['category']);
            Posts::set_meta($id, 'brand', $request['brand']);
            Posts::set_meta($id, 'quantity_box', $request['quantity_box']);
            Posts::set_meta($id, 'weight_item', $request['weight_item']);
            Posts::set_meta($id, 'price', $request['price']);
            Posts::set_meta($id, 'price_box', $request['price_box']);
            Posts::set_meta($id, 'cost_price', $request['cost_price']);

            return $id;
        }


        if (strtolower($post_type['post_type']) === 'sales') {

            $request = Request::require(
                'name',
                'desc',
                'content',
                'post_type',
                'comments',
                'visible',
                'mime_type',
                'slug',

                'customer',
                'grand_total',
                'sub_total',
                'tax',
                'discount',
                'shipping',
                'payment_method',
                'sale_status',
                'due'
            );

            $id = $this->model->add_post(
                Essentials::id(),
                $request['name'],
                $request['desc'],
                $request['content'],
                $request['post_type'],
                $request['comments'],
                $request['visible'],
                $request['mime_type'],
                $request['slug']
            );

            $contents = json_decode($request['content']);
            foreach ($contents as $content) {
                $per_box = Posts::get_meta($content->product, 'quantity_box');
                $to_remove = $content->carton * $per_box->value;
                $product = Posts::get_post($content->product);
                $new_count = $product->content - $to_remove;

                $this->model->update_content($content->product, $new_count);
            }

            Posts::set_meta($id, 'customer', $request['customer']);
            Posts::set_meta($id, 'grand_total', $request['grand_total']);
            Posts::set_meta($id, 'sub_total', $request['sub_total']);
            Posts::set_meta($id, 'tax', $request['tax']);
            Posts::set_meta($id, 'discount', $request['discount']);
            Posts::set_meta($id, 'shipping', $request['shipping']);
            Posts::set_meta($id, 'payment_method', $request['payment_method']);
            Posts::set_meta($id, 'sale_status', $request['sale_status']);
            Posts::set_meta($id, 'due', $request['due']);

            return $id;
        }


        if (strtolower($post_type['post_type']) === 'adjustment') {

            $request = Request::require(
                'name',
                'desc',
                'content',
                'post_type',
                'comments',
                'visible',
                'mime_type',
                'slug',
            );

            $id = $this->model->add_post(
                Essentials::id(),
                $request['name'],
                $request['desc'],
                $request['content'],
                $request['post_type'],
                $request['comments'],
                $request['visible'],
                $request['mime_type'],
                $request['slug']
            );

            $contents = json_decode($request['content']);
            foreach ($contents as $content) {
                $per_box = Posts::get_meta($content->product, 'quantity_box');
                $to_add = $content->carton * $per_box->value;
                $product = Posts::get_post($content->product);
                $new_count = $product->content + $to_add;

                $this->model->update_content($content->product, $new_count);
            }

            return $id;
        }
    }

    public function remove_post()
    {
        Session::check([0, 1, 2, 3]);
        $request = Request::require('id');

        $name = Posts::get_meta($request['id'], 'image');

        if ($name->value) {
            Images::remove($name->value);
        }

        return $this->model->remove_post($request['id']);
    }

    public function update_post()
    {
        Session::check([0, 1, 2, 3, 3, 4]);

        $post_type = Request::require('post_type');

        if (isset($post_type['post_type']) && strtolower($post_type['post_type']) === 'products') {
            $request = Request::require(
                'id',
                'title',
                'description',
                'content',
                'post_type',
                'comments',
                'visible',
                'mime_type',
                'slug',
                'category',
                'brand',
                'quantity_box',
                'weight_item',
                'price',
                'price_box',
                'cost_price',
                'image'
            );


            if (strlen(trim($request['image']))) {

                $path = Images::add(
                    trim($request['image']),
                    300,
                    300,
                    'webp'
                );

                $old = Posts::get_meta($request['id'], 'image');
                if (strlen($old->value)) {
                    Posts::update_meta($request['id'], 'image', $path);
                    Images::remove($old->value);
                } else {
                    Posts::set_meta($request['id'], 'image', $path);
                }
            }



            $old = Posts::get_meta($request['id'], 'cost_price');
            if ($old && strlen($old->value)) {
                Posts::update_meta($request['id'], 'cost_price', $request['cost_price']);
            } else {
                Posts::set_meta($request['id'], 'cost_price', $request['cost_price']);
            }

            $old = Posts::get_meta($request['id'], 'brand');
            if ($old && strlen($old->value)) {
                Posts::update_meta($request['id'], 'brand', $request['brand']);
            } else {
                Posts::set_meta($request['id'], 'brand', $request['brand']);
            }

            Posts::update_meta($request['id'], 'price_box', $request['price_box']);
            Posts::update_meta($request['id'], 'price', $request['price']);
            Posts::update_meta($request['id'], 'weight_item', $request['weight_item']);
            Posts::update_meta($request['id'], 'quantity_box', $request['quantity_box']);
            Posts::update_meta($request['id'], 'category', $request['category']);

            return Response::new(200, $this->model->update_post(
                $request['id'],
                $request['title'],
                $request['description'],
                $request['content'],
                $request['post_type'],
                $request['comments'],
                $request['visible'],
                $request['mime_type'],
                $request['slug']
            ));
        }


        if (isset($post_type['post_type']) && strtolower($post_type['post_type']) === 'sales') {
        }
    }
}
