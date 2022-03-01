<?php

namespace Jara\App\Models;

use Jara\App\Configs\Config;
use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Setting;

class ThemeModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
        $this->api = Config::$app_url;
    }

    public function set_themes($inputs)
    {
        $old_settings = json_decode($this->get_themes());

        foreach ($inputs as $key => $value) {
            if (strpos($value, 'data:image/') === 0) {

                if ($old_settings->{$key} != '') {
                    $remove = str_replace($this->api . '/uploads/images/', '', $old_settings->{$key});
                    Images::remove($remove);
                }
                $value = Images::add_fullsized($value);
            } elseif (strpos($value, 'http') === 0) {
                $value =  str_replace($this->api . '/uploads/images/', '', $value);
            }


            if (strpos($key, 'theme_') === 0) {
                $key = $key;
            } else {
                $key = "theme_" . $key;
            }
            $key = Essentials::camel_to_snake($key);
            if (Setting::get($key)->id) {
                Setting::update($key, $value);
            } else {
                Setting::set($key, $value);
            }
        }

        return true;
    }

    public function get_themes()
    {
        $settings = Setting::get_all();
        $data = [];
        foreach ($settings as $setting) {
            if (strpos($setting->key, 'theme_') === 0) {

                $pos = strpos($setting->key, 'theme_');
                if ($pos !== false) {
                    $key = substr_replace($setting->key, '', $pos, strlen('theme_'));
                }
                $data[Essentials::snake_to_camel($key)] = $setting->value;
            }
        }

        $data['themeInverted'] = true;
        $data['themeBodyPadded'] = false;
        $data['footerLogo'] = $this->api . '/uploads/images/' . $data['footerLogo'];
        $data['headerLogoLight'] = $this->api . '/uploads/images/' . $data['headerLogoLight'];
        $data['headerLogoDark'] = $data['headerLogoLight'];
        // $data['headerLogoStyle'] = 'VERTICAL';
        // $data['headerLogoBackground'] = 'LIGHT';

        return json_encode($data);
    }
}
