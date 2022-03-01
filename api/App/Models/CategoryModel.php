<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Categories;
use Jara\Core\Helpers\Posts;

class CategoryModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }


    /**
     * Category Model
     */

    public function set_category($object)
    {

        if (strlen($object['image']['data']) > 5) {
            $object['image']['url'] = Images::add($object['image']['data'], 337, 900, 'webp');
        }

        // remove data from object
        unset($object['image']['data']);

        $id = Categories::add(
            $object['name'],
            json_encode($object['image']),
        );

        Categories::set_meta($id, 'options', json_encode($object['options']));
        Categories::set_meta($id, 'order', $object['order']);

        return Response::new(200, $id);
    }

    public function update_category($object)
    {
        // get the old image url
        $old_image = json_decode($this->get_single_category($object['id'])->description);

        if (isset($object['image']['data']) && strlen($object['image']['data']) > 5) {
            if (isset($old_image->url)) {
                Images::remove($old_image->url);
            }
            $object['image']['url'] = '';
            $object['image']['url'] = Images::add($object['image']['data'], 337, 900, 'webp');

            // remove data from object
            unset($object['image']['data']);

            Categories::update(
                $object['id'],
                $object['name'],
                json_encode($object['image']),
            );
        } else {
            Categories::update(
                $object['id'],
                $object['name'],
                json_encode($old_image),
            );
        }



        Categories::update_meta($object['id'], 'options', json_encode($object['options']));
        if (Categories::get_meta($object['id'], 'order')) {
            Categories::update_meta($object['id'], 'order', $object['order']);
        } else {
            Categories::set_meta($object['id'], 'order', $object['order']);
        }

        return Response::new(200, $object['id']);
    }

    public function remove_category($id)
    {

        // get the old image url
        $old_image = json_decode($this->get_single_category($id)->description);

        if (isset($old_image->url)) {
            Images::remove($old_image->url);
        }

        return Response::new(200, Categories::remove($id));
    }

    public function get_category()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `categories`");

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    public function get_single_category($id)
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `categories` WHERE `id` = :id");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }

    public function get_products_by_cat($cat_id)
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT post_id FROM `post_meta` WHERE `meta_key` = 'category_id' AND `meta_value` = :cat_id");

        $query->bindParam(':cat_id', $cat_id);

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }


    public function remove_product($id)
    {

        // get the old image url
        $old_image = json_decode(Posts::get_meta($id, 'image')->value);

        if (isset($old_image->url)) {
            Images::remove($old_image->url);
        }
        return Response::new(200, Posts::remove($id));
    }
}
