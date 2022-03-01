<?php

namespace Jara\Core;

use Jara\Core\Database\Connect;
use Jara\Core\Router\Router;

class Start
{
    public $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->db = new Connect();
    }

    public function view(String $name)
    {
        include __DIR__ . "/Views/$name.php";
    }
}
