<?php

namespace Jara\App\Views;

use Jara\App\Controllers\ContactController;

class ContactView
{
    public $controller;

    function __construct()
    {
        $this->controller = new ContactController();
    }

    public function set_contact()
    {
        echo $this->controller->set_contact();
    }

    public function get_contact()
    {
        echo $this->controller->get_contact();
    }
}
