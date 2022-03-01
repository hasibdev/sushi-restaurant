<?php

namespace Jara\App\Views;

use Jara\App\Configs\Config;
use Jara\App\Controllers\ThemeController;

class ThemeView
{
    public $controller;

    function __construct()
    {
        $this->controller = new ThemeController();
        $this->api = Config::$app_url;
    }

    public function get_themes()
    {
        echo $this->controller->get_themes();
    }

    public function set_themes()
    {
        echo $this->controller->set_themes();
    }
}
