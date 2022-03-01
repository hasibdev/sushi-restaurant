<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;

class EmailModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function get_order($order_id)
    {
        $sql = "SELECT * FROM posts WHERE post_type = 'orders' AND id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $order_id);
        $stmt->execute();
        $order = $stmt->fetch();
        return $order;
    }
}
