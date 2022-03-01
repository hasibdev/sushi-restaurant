<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Setting;

class ContactModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function set_contact($data)
    {
        $old = Setting::get('page_contact', '');

        if ($old == '') {
            $result = Setting::set('page_contact', json_encode($data));
        } else {
            $result = Setting::update('page_contact', json_encode($data));
        }

        return $result;
    }

    public function get_contact()
    {
        $result = Setting::get('page_contact', '');

        return $result->value;
    }

    public function get_location($id)
    {
        $result = $this->db->query("SELECT * FROM `posts` WHERE post_type = 'locations' AND id = $id");

        return $result->fetch();
    }
}
