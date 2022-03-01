<?php

namespace Jara\App\Views;

use Jara\App\Configs\Config;
use Jara\App\Controllers\MenuController;

class MenuView
{
  public $controller;

  function __construct()
  {
    $this->controller = new MenuController();
    $this->api = Config::$app_url;
  }


  /**
   * Products view
   */

  public function get_items()
  {
    echo $this->controller->get_items();
  }

  public function get_item($id)
  {
    echo $this->controller->get_item($id);
  }

  public function set_items()
  {
    echo $this->controller->set_items();
  }

  public function update_items()
  {
    echo $this->controller->update_items();
  }

  public function remove_items()
  {
    echo $this->controller->remove_items();
  }

  public function get_offers()
  {
    echo '[
            {
              "id": 1,
              "name": "Free Small Pizza",
              "description": "Get free small pizza orders higher that $40!",
              "type": "FREE_PRODUCT",
              "freeProductId": 114,
              "conditions": [
                {
                  "id": 14,
                  "name": "Only on Weekends",
                  "type": "DAY",
                  "value": [0, 6]
                },
                {
                  "id": 15,
                  "name": "Order higher than $40",
                  "type": "MINIMUM_ORDER_VALUE",
                  "value": 40
                },
                {
                  "id": 16,
                  "name": "Unless one Pizza in a cart",
                  "type": "CATEGORY_ID",
                  "value": [53]
                }
              ],
              "image": {
                "name": "Image Title",
                "url": "' . $this->api . '/uploads/images/3.jpeg"
              }
            },
            {
              "id": 2,
              "name": "Chip Friday",
              "description": "10% Off for all dishes!",
              "type": "DISCOUNT",
              "discountValue": 0.1,
              "conditions": [
                {
                  "id": 17,
                  "name": "Only on Friday",
                  "type": "DAY",
                  "value": [5]
                },
                {
                  "id": 18,
                  "name": "All products",
                  "type": "CATEGORY_ID",
                  "value": [1, 2, 3, 4, 5, 6]
                }
              ],
              "image": {
                "name": "Image Title",
                "url": "' . $this->api . '/uploads/images/1.jpeg"
              }
            }
          ]';
  }
}
