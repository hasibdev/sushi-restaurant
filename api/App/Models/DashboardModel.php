<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;

class DashboardModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function get_all_orders($year = null)
    {
        if ($year == null) {
            $sql = "SELECT * FROM `posts` WHERE `post_type` = 'orders'";
        } else {
            $sql = "SELECT * FROM `posts` WHERE `post_type` = 'orders' AND YEAR(datetime) = :year";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function get_order_by_date_range($start_date, $end_date)
    {
        $sql = "SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `datetime` BETWEEN :start_date AND :end_date";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}
