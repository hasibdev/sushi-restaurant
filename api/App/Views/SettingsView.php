<?php

namespace Jara\App\Views;

use Jara\App\Controllers\SettingsController;

class SettingsView
{
  public $controller;

  function __construct()
  {
    $this->controller = new SettingsController();
  }

  public function set_settings()
  {
    echo  $this->controller->set_settings();
  }

  public function get_settings()
  {
    echo $this->controller->get_settings();

    // echo '
    //     {
    //       "address": {
    //         "street": "St John 21/52",
    //         "city": "Carcow",
    //         "contry": "Poland"
    //       },
    //       "phone": "+48 432 543 666",
    //       "email": "hello@example.com",
    //       "currency": "USD",
    //       "currencySymbol": "$",
    //       "deliveryAreas": [
    //         {
    //           "zipCode": "30-002",
    //           "city": "Carcow - Old Town",
    //           "deliveryCost": 9
    //         },
    //         {
    //           "zipCode": "30-007",
    //           "city": "Carcow - Center",
    //           "deliveryCost": 5.5
    //         }
    //       ],
    //       "deliveryTimeOptions": [
    //         {
    //           "name": "As fast as possible",
    //           "value": 0
    //         },
    //         {
    //           "name": "In one hour",
    //           "value": 1
    //         },
    //         {
    //           "name": "In two hours",
    //           "value": 2
    //         }
    //       ],
    //       "isOpen": true,
    //       "minimumCartValue": 20,
    //       "openHours": [
    //         {
    //           "days": [0],
    //           "openHour": "12:00",
    //           "closeHour": "22:00"
    //         },
    //         {
    //           "days": [1, 2, 3, 4, 5],
    //           "openHour": "8:00",
    //           "closeHour": "20:00"
    //         },
    //         {
    //           "days": [6],
    //           "openHour": "12:00",
    //           "closeHour": "24:00"
    //         }
    //       ],
    //       "socialMedia": [
    //         {
    //           "type": "facebook",
    //           "url": "#"
    //         },
    //         {
    //           "type": "google",
    //           "url": "#"
    //         },
    //         {
    //           "type": "twitter",
    //           "url": "#"
    //         },
    //         {
    //           "type": "instagram",
    //           "url": "#"
    //         },
    //         {
    //           "type": "linkedin",
    //           "url": "#"
    //         }
    //       ],
    //       "menuDefaultView": "/menu-grid-navigation",
    //       "navigation": [{"name":"Home","path":"/"},{"name":"Menu","path":"/menu-grid-navigation"},{"name":"Offers","path":"/offers"},{"name":"Contact","path":"/contact"}],
    //       "meta": {
    //         "title": "iWebDesigner",
    //         "description": "Built with JaraCMS by iWebDesigner",
    //         "keywords": "Restaurant, Sushi"
    //       }
    //     }';
  }
}
