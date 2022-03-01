<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Posts;

class LocationsModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function get_location($id)
    {
        return Posts::get_post($id);
    }

    public function get_locations()
    {
        $sql = "SELECT * FROM `posts` WHERE `post_type` = 'locations'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $new_results = [];
        $reverse = true;
        foreach ($results as $result) {
            $output = json_decode($result->content);
            $output->id = $result->id;
            $new_results[] = $output;
        }

        return $new_results;
    }

    public function set_location($content)
    {
        return Posts::add(Essentials::id(), 'locations', '', json_encode($content), 'locations');
    }

    public function update_location($id, $content)
    {
        return Posts::update($id, 'locations', '', json_encode($content), 'locations', 0, 1, 'plain/text', null);
    }

    public function delete_location($id)
    {
        return Posts::remove($id);
    }
}
