<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;

class ShopModel
{
    public $db; 
    
    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }
}