<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Response;


class AdditionModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }


    /**
     * Additions Model
     */


    public function set_additions($object)
    {
        $id = Posts::add(
            Essentials::id(),
            $object['name'],
            json_encode($object['image']),
            $object['price'],
            'additions',
        );

        return Response::new(200, $id);
    }

    public function get_additions()
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'additions'");

        $query->execute();

        $result = $query->fetchAll();

        return $result;
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

    public function update_additions($object)
    {
        Posts::update(
            $object['id'],
            $object['name'],
            json_encode($object['image']),
            $object['price'],
            'additions',
            false,
            true,
            'text/plain',
            null
        );

        return Response::new(200, $object['id']);
    }

    public function remove_additions($id)
    {
        return Response::new(200, Posts::remove($id));
    }
}
