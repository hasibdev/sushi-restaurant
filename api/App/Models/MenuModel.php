<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Response;

class MenuModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    /**
     * Products Model
     */

    public function set_items($object)
    {
        $id = Posts::add(
            Essentials::id(),
            $object['name'],
            $object['description'],
            $object['price'],
            'products',
        );

        Posts::set_meta(
            $id,
            'options',
            json_encode($object['options']),
        );

        Posts::set_meta(
            $id,
            'additions',
            json_encode($object['additions']),
        );

        if (isset($object['image']['data']) && strlen($object['image']['data']) > 5) {
            $object['image']['url'] = Images::add($object['image']['data'], 250, 375, 'webp');

            // remove data from object
            unset($object['image']['data']);
        } else {
            $object['image']['url'] = null;

            unset($object['image']['data']);
        }

        Posts::set_meta(
            $id,
            'order',
            $object['order'],
        );

        Posts::set_meta(
            $id,
            'image',
            json_encode($object['image']),
        );

        Posts::set_meta(
            $id,
            Essentials::camel_to_snake('categoryId'),
            $object['categoryId'],
        );

        return Response::new(200, $id);
    }

    public function get_items()
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'products'");

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    public function get_item($id)
    {
        $id = $id[0];

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `id` = :id AND `post_type` = 'products'");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }

    public function update_items($object)
    {
        Posts::update(
            $object['id'],
            $object['name'],
            $object['description'],
            $object['price'],
            'products',
            false,
            true,
            'text/plain',
            null
        );

        Posts::update_meta(
            $object['id'],
            'options',
            json_encode($object['options']),
        );

        Posts::update_meta(
            $object['id'],
            'additions',
            json_encode($object['additions']),
        );


        if (Posts::get_meta($object['id'], 'order')) {

            Posts::update_meta(
                $object['id'],
                'order',
                $object['order'],
            );
        } else {

            Posts::set_meta(
                $object['id'],
                'order',
                $object['order'],
            );
        }



        if (isset($object['image']['data']) && strlen($object['image']['data']) > 5) {

            // get the old image url
            $old_image = json_decode(Posts::get_meta($object['id'], 'image')->value);

            if (isset($old_image->url)) {
                Images::remove($old_image->url);
            }

            $object['image']['url'] = Images::add($object['image']['data'], 250, 375, 'webp');

            // remove data from object
            unset($object['image']['data']);



            Posts::update_meta(
                $object['id'],
                'image',
                json_encode($object['image']),
            );
        }

        Posts::update_meta(
            $object['id'],
            Essentials::camel_to_snake('categoryId'),
            $object['categoryId'],
        );

        return Response::new(200, $object['id']);
    }

    public function remove_items($id)
    {

        // get the old image url
        $old_image = json_decode(Posts::get_meta($id, 'image')->value);

        if (isset($old_image->url)) {
            Images::remove($old_image->url);
        }
        return Response::new(200, Posts::remove($id));
    }


    public function get_addition($id)
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'additions' AND `id` = :id");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }


    public function get_option($id)
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'options' AND `id` = :id");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

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

        return $schema;
    }
}
