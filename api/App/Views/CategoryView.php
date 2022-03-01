<?php

namespace Jara\App\Views;

use Jara\App\Controllers\CategoryController;

class CategoryView
{
    public $controller;

    function __construct()
    {
        $this->controller = new CategoryController();
    }


    public function get_categories()
    {
        echo '[{"id":1,"name":"Burgers","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/menu-title-burgers.jpg"}},{"id":2,"name":"Pizza","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/menu-title-pizza.jpg"}},{"id":3,"name":"Sushi","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/menu-title-sushi.jpg"}},{"id":4,"name":"Pasta","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/menu-title-pasta.jpg"}},{"id":5,"name":"Desserts","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/menu-title-desserts.jpg"}},{"id":6,"name":"Drinks","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/menu-title-drinks.jpg"}}]';
    }


    /**
     * Categories view
     */


    public function get_category()
    {
        echo $this->controller->get_category();
    }

    public function get_single_category($params)
    {
        echo $this->controller->get_single_category($params[0]);
    }

    public function set_category()
    {
        echo $this->controller->set_category();
    }

    public function update_category()
    {
        echo $this->controller->update_category();
    }

    public function remove_category()
    {
        echo $this->controller->remove_category();
    }
}
