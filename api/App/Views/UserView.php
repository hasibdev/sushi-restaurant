<?php

namespace Jara\App\Views;

use Jara\App\Controllers\UserController;

class UserView
{
    public $controller;

    function __construct()
    {
        $this->controller = new UserController();
    }

    public function get_all_users()
    {
        echo $this->controller->get_all_users();
    }

    public function get_user($params)
    {
        echo $this->controller->get_user($params[0]);
    }

    public function add_user()
    {
        echo $this->controller->add_user();
    }

    public function remove_user()
    {
        echo $this->controller->remove_user();
    }

    public function update_user()
    {
        echo $this->controller->update_user();
    }

    public function search_user($params)
    {
        echo $this->controller->search_user($params[0]);
    }
}
