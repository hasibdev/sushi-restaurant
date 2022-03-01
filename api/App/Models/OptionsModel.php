<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Response;

class OptionsModel
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


    public function set_options($object)
    {
        $id = Posts::add(
            Essentials::id(),
            $object['name'],
            '',
            json_encode($object['list']),
            'options',
        );

        return Response::new(200, $id);
    }

    public function get_options()
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'options'");

        $query->execute();

        $result = $query->fetchAll();

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

        return $result;
    }

    public function update_options($object)
    {
        Posts::update(
            $object['id'],
            $object['name'],
            '',
            json_encode($object['list']),
            'options',
            false,
            true,
            'text/plain',
            null
        );

        return Response::new(200, $object['id']);
    }

    public function remove_options($id)
    {
        return Response::new(200, Posts::remove($id));
    }
}
