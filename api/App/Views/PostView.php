<?php

namespace Jara\App\Views;

use Jara\App\Controllers\PostController;

class PostView
{
    public $controller;

    function __construct()
    {
        $this->controller = new PostController();
    }


    public function get_all_posts()
    {
        echo $this->controller->get_all_posts();
    }

    public function add_post()
    {
        echo $this->controller->add_post();
    }

    public function remove_post()
    {
        echo $this->controller->remove_post();
    }

    public function update_post()
    {
        echo $this->controller->update_post();
    }
}
