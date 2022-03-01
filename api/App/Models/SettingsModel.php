<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Setting;

class SettingsModel
{
  public $db;

  function __construct()
  {
    $this->db = new Connect();
    $this->db = $this->db->connect();
  }

  public function set_settings($inputs)
  {

    foreach ($inputs as $key => $value) {
      if (strpos($key, 'settings_') === 0) {
        $key = $key;
      } else {
        $key = "settings_" . $key;
      }
      $key = Essentials::camel_to_snake($key);

      if (is_array($value)) {
        $value = json_encode($value);
      }

      if (Setting::get($key)->id) {
        Setting::update($key, $value);
      } else {
        Setting::set($key, $value);
      }
    }

    return true;
  }

  public function get_settings()
  {
    $settings = Setting::get_all();
    $data = [];
    foreach ($settings as $setting) {
      if (strpos($setting->key, 'settings_') === 0) {

        $pos = strpos($setting->key, 'settings_');
        if ($pos === 0) {
          $key = substr_replace($setting->key, '', $pos, strlen('settings_'));
        }
        $data[Essentials::snake_to_camel($key)] = $setting->value;
      }
    }

    // print_r($data);
    $open = ($data['isOpen'] == 1) ? 1 : 0;

    // if open check if it's break time
    if ($open == 1) {
      $contact = Setting::get('page_contact', '');
      $contact = json_decode($contact->value);

      $start = strtotime($contact->breakTime->start);
      $end = strtotime($contact->breakTime->end);

      if (time() >= $start && time() <= $end) {
        $open = 0;
      } else {
        $open = 1;
      }
    }

    $locations = new LocationsModel();
    $locations = $locations->get_locations();

    $new_locations = [];

    foreach ($locations as $location) {
      $new_locations[] = [
        'zipCode' => 'N/A',
        'city' => $location->name . ', ' . $location->address->street . ', ' . $location->address->city . ', ' . $location->address->contry,
        'deliveryCost' => "0"
      ];
    }

    $locations = null;

    return '
        {
          "address": ' . $data['address'] . ',
          "phone": "' . $data['phone'] . '",
          "email": "' . $data['email'] . '",
          "currency": "' . $data['currency'] . '",
          "currencySymbol": "' . $data['currencySymbol'] . '",
          "deliveryAreas": ' . $data['deliveryAreas'] . ',
          "pickupAreas": ' . json_encode($new_locations) . ',
          "deliveryTimeOptions": ' . $data['deliveryTimeOptions'] . ',
          "tax": ' . $data['tax'] . ',
          "isOpen": ' . $open . ',
          "minimumCartValue": ' . intval($data['minimumCartValue']) . ',
          "openHours": ' . $data['openHours'] . ',
          "socialMedia": ' . $data['socialMedia'] . ',
          "menuDefaultView": "/menu-grid-navigation",
          "navigation": [{"name":"Home","path":"/"},{"name":"Offers","path":"/offers"},{"name":"Contact","path":"/contact"}],
          "meta": ' . $data['meta'] . '
        }';


    // {"name":"Menu","path":"/menu-grid-navigation"},
  }
}
