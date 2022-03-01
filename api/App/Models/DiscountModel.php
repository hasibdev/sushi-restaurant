<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Response;

class DiscountModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    /**
     * Discount Model
     */


    public function set_discounts($object)
    {
        $id = Posts::add(
            Essentials::id(),
            $object['name'],
            $object['code'],
            $object['value'],
            'discounts',
            0,
            1,
            'text/plain',
            $object['expire_date'],
        );

        return Response::new(200, $id);
    }

    public function get_discounts()
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'discounts';");

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    public function get_discount($id)
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'discounts' AND `description` = :id");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }

    public function update_discounts($object)
    {
        Posts::update(
            $object['id'],
            $object['name'],
            $object['code'],
            $object['value'],
            'discounts',
            0,
            1,
            'text/plain',
            $object['expire_date']
        );

        return Response::new(200, $object['id']);
    }

    public function remove_discounts($id)
    {
        return Response::new(200, Posts::remove($id));
    }
}
