<?php

namespace Jara\App\Views;

use Jara\App\Controllers\OfferController;

class OfferView
{
    public $controller;

    function __construct()
    {
        $this->controller = new OfferController();
    }



    /**
     * Offer view
     */

    public function get_offers()
    {
        echo $this->controller->get_offers();
    }

    public function get_offer($id)
    {
        echo $this->controller->get_offer($id);
    }

    public function set_offers()
    {
        echo $this->controller->set_offers();
    }

    public function update_offers()
    {
        echo $this->controller->update_offers();
    }

    public function remove_offers()
    {
        echo $this->controller->remove_offers();
    }
}
