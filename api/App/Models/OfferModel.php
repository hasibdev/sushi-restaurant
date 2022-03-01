<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Response;

class OfferModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }


    /**
     * Offers Model
     */

    public function set_offers($object)
    {
        $id = Posts::add(
            Essentials::id(),
            $object['name'],
            $object['description'],
            $object['type'],
            'offers',
        );

        if (strtoupper($object['type']) === "DISCOUNT") {
            if (intval($object['discountValue']) > 1) {
                $object['discountValue'] = intval($object['discountValue']) / 100;
            }

            Posts::set_meta(
                $id,
                'discount_value',
                $object['discountValue'],
            );
        } elseif ($object['type'] === "FREE_PRODUCT") {
            Posts::set_meta(
                $id,
                'free_product_id',
                intval($object['freeProductId']),
            );
        }

        Posts::set_meta(
            $id,
            'conditions',
            json_encode($object['conditions']),
        );

        if (isset($object['image']['data']) && strlen($object['image']['data']) > 5) {
            $object['image']['url'] = Images::add($object['image']['data'], 420, 600, 'webp');

            // remove data from object
            unset($object['image']['data']);
        } else {
            $object['image']['url'] = null;

            unset($object['image']['data']);
        }

        Posts::set_meta(
            $id,
            'image',
            json_encode($object['image']),
        );

        return Response::new(200, $id);
    }

    public function get_offers()
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'offers'");

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    public function get_offer($id)
    {
        $id = $id[0];

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `id` = :id AND `post_type` = 'offers'");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }

    public function update_offers($object)
    {
        Posts::update(
            $object['id'],
            $object['name'],
            $object['description'],
            $object['type'],
            'offers',
            false,
            true,
            'text/plain',
            null
        );

        if (strtoupper($object['type']) === "DISCOUNT") {
            if (intval($object['discountValue']) > 1) {
                $object['discountValue'] = intval($object['discountValue']) / 100;
            }

            Posts::update_meta(
                $object['id'],
                'discount_value',
                $object['discountValue'],
            );
        } elseif ($object['type'] === "FREE_PRODUCT") {
            Posts::update_meta(
                $object['id'],
                'free_product_id',
                intval($object['freeProductId']),
            );
        }

        Posts::update_meta(
            $object['id'],
            'conditions',
            json_encode($object['conditions']),
        );

        if (isset($object['image']['data']) && strlen($object['image']['data']) > 5) {

            // get the old image url
            $old_image = json_decode(Posts::get_meta($object['id'], 'image')->value);

            if (isset($old_image->url)) {
                Images::remove($old_image->url);
            }

            $object['image']['url'] = Images::add($object['image']['data'], 420, 600, 'webp');

            // remove data from object
            unset($object['image']['data']);

            Posts::update_meta(
                $object['id'],
                'image',
                json_encode($object['image']),
            );
        }


        return Response::new(200, $object['id']);
    }

    public function remove_offers($id)
    {

        // get the old image url
        $old_image = json_decode(Posts::get_meta($id, 'image')->value);

        if (isset($old_image->url)) {
            Images::remove($old_image->url);
        }
        return Response::new(200, Posts::remove($id));
    }
}
