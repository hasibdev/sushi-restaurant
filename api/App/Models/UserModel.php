<?php

namespace Jara\App\Models;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Users;

class UserModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function get_all_users()
    {
        if (isset($_GET['count'])) {
            $query = $this->db->prepare('SELECT `id` FROM `users`;');

            $query->execute();

            return $query->rowCount();
        } elseif (isset($_GET['page'])) {
            $offset = (intval($_GET['page']) - 1) * 10;
            $query = $this->db->prepare('SELECT `id`, `username`, `email`, `phone`, `full_name`, `last_seen`, `datetime`, 
                (SELECT `role_name` FROM `user_roles` WHERE `role_code` = `users`.`role`) AS role
                FROM `users` LIMIT :offset, 10;');

            $query->bindParam(':offset', $offset, \PDO::PARAM_INT);
        } elseif (isset($_GET['search']) && trim($_GET['search']) !== '') {
            $key = '%' . $_GET['search'] . '%';
            $query = $this->db->prepare("SELECT `id`, `username`, `email`, `phone`, `full_name`, `last_seen`, `datetime`, 
                (SELECT `role_name` FROM `user_roles` WHERE `role_code` = `users`.`role`) AS role
                FROM `users` WHERE username LIKE :key OR email LIKE :key OR full_name LIKE :key OR phone LIKE :key;");

            $query->bindParam(':key', $key, \PDO::PARAM_STR);
            $query->bindParam(':key', $key, \PDO::PARAM_STR);
            $query->bindParam(':key', $key, \PDO::PARAM_STR);
            $query->bindParam(':key', $key, \PDO::PARAM_STR);
        } else {
            $query = $this->db->prepare('SELECT `id`, `username`, `email`, `phone`, `full_name`, `last_seen`, `datetime`, 
                (SELECT `role_name` FROM `user_roles` WHERE `role_code` = `users`.`role`) AS role
                FROM `users`;');
        }

        $query->execute();

        $results = $query->fetchAll();

        $newResults = [];
        foreach ($results as $result) {
            $result->full_name = mb_convert_encoding($result->full_name, "UTF-8");
            $result->email = mb_convert_encoding($result->email, "UTF-8");
            $result->username = mb_convert_encoding($result->username, "UTF-8");
            $metas  = Users::get_all_meta($result->id);
            foreach ($metas as $value) {
                if (!empty($value)) {
                    $result->meta[$value->key] = $value->value;
                }
            }
            array_push($newResults, $result);
        }

        return $newResults;
    }


    public function search_user($string)
    {
        $string = trim($string);

        if (empty($string)) {
            return [];
        } else {
            $string = '%' . $string . '%';
        }

        $query = $this->db->prepare("SELECT `id`, `username`, `email`, `phone`, `full_name`
            FROM `users` WHERE username LIKE :string OR email LIKE :string OR full_name LIKE :string OR phone LIKE :string;");

        $query->bindParam(':string', $string, \PDO::PARAM_STR);
        $query->bindParam(':string', $string, \PDO::PARAM_STR);
        $query->bindParam(':string', $string, \PDO::PARAM_STR);
        $query->bindParam(':string', $string, \PDO::PARAM_STR);

        $query->execute();

        $results = $query->fetchAll();

        $newResults = [];

        foreach ($results as $result) {
            $result->full_name = mb_convert_encoding($result->full_name, "UTF-8");
            $result->email = mb_convert_encoding($result->email, "UTF-8");
            $result->username = mb_convert_encoding($result->username, "UTF-8");

            array_push($newResults, $result);
        }

        return $newResults;
    }

    public function get_user($id)
    {

        $id = trim($id);

        $query = $this->db->prepare('SELECT `id`, `username`, `email`, `phone`, `full_name`, `last_seen`, `datetime`, 
        (SELECT `role_name` FROM `user_roles` WHERE `role_code` = `users`.`role`) AS role
        FROM `users` WHERE id = :id OR username = :id OR email = :id OR phone = :id;');

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        if ($result) {

            $result->full_name = mb_convert_encoding($result->full_name, "UTF-8");
            $result->email = mb_convert_encoding($result->email, "UTF-8");
            $result->username = mb_convert_encoding($result->username, "UTF-8");
            $metas  = Users::get_all_meta($result->id);
            foreach ($metas as $value) {
                if (!empty($value)) {
                    $result->meta[$value->key] = $value->value;
                }
            }

            return $result;
        } else {
            return false;
        }
    }

    public function add_user($username, $email, $full_name, $password, $phone, $role = 5)
    {
        return Users::add($username, $email, $full_name, $password, $phone, $role);
    }

    public function remove_user($id)
    {
        return Users::remove($id);
    }

    public function update_user($id, $name, $username, $email, $phone, $role)
    {
        Users::set_email($id, $email);
        Users::set_username($id, $username);
        Users::set_phone($id, $phone);
        Users::set_full_name($id, $name);
        Users::set_role($id, $role);

        return true;
    }


    public function is_unique_email($email)
    {

        $email = strtolower($email);

        $query = $this->db->prepare('SELECT `id` FROM `users` WHERE email = :email');

        $query->bindParam(':email', $email);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function is_unique_phone($phone)
    {

        $phone = preg_replace('/[^0-9.]/', '', trim($phone));

        $query = $this->db->prepare('SELECT `id` FROM `users` WHERE phone = :phone');

        $query->bindParam(':phone', $phone);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function is_unique_username($username)
    {

        $username = strtolower($username);

        $query = $this->db->prepare('SELECT `id` FROM `users` WHERE username = :username');

        $query->bindParam(':username', $username);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
