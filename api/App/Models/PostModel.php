<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Posts;

class PostModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function add_post($user_id, $title, $desc, $content, $post_type, $comments = 0, $visible = 1, $mime_type = 'text/plain', $slug = null)
    {
        return Posts::add($user_id, $title, $desc, $content, $post_type, $comments, $visible, $mime_type, $slug);
    }

    public function remove_post($id)
    {
        return Posts::remove($id);
    }

    public function get_all_posts($type)
    {
        if (isset($_GET['count'])) {
            $query = $this->db->prepare('SELECT `id` FROM `posts` WHERE post_type = :type;');

            $query->bindParam(':type', $type);

            $query->execute();

            return $query->rowCount();
        } elseif (isset($_GET['id'])) {

            $query = $this->db->prepare('SELECT * FROM `posts` WHERE id = :id AND post_type = :type;');

            $query->bindParam(':id', $_GET['id']);
            $query->bindParam(':type', $type);

            $query->execute();

            $result = $query->fetch();

            $metas  = Posts::get_all_meta($result->id);
            foreach ($metas as $value) {
                $result->meta[$value->key] = $value->value;
            }

            return $result;
        } else {

            $query = $this->db->prepare('SELECT * FROM `posts` WHERE post_type = :type;');

            $query->bindParam(':type', $type);

            $query->execute();

            $results = $query->fetchAll();

            $newResults = [];
            foreach ($results as $result) {
                $metas  = Posts::get_all_meta($result->id);
                foreach ($metas as $value) {
                    $result->meta[$value->key] = $value->value;
                }
                array_push($newResults, $result);
            }

            return $newResults;
        }
    }

    public function update_post($id,  $title, $desc, $content, $post_type, $comments = 0, $visible = 1, $mime_type = 'text/plain', $slug = null)
    {
        return Posts::update($id,  $title, $desc, $content, $post_type, $comments, $visible, $mime_type, $slug);
    }

    public function update_content($id, $content)
    {
        $query = $this->db->prepare("UPDATE `posts` SET
        `content` = :content
        WHERE `id` = :id;");

        $query->bindParam(':content', $content);
        $query->bindParam(':id', $id);

        $query->execute();
    }
}
