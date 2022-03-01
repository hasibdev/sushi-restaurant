<?php

namespace Jara\Core\Helpers;

use Jara\Core\Database\Connect;

class Essentials
{
    public static $db;

    public static function timestamp()
    {
        date_default_timezone_set('Asia/Dhaka');
        return strtotime(date('c'));
    }

    public static function datetime()
    {
        date_default_timezone_set('Asia/Dhaka');
        return date("Y-m-d H:i:s", time());
    }

    public static function otp($min = 1000, $max = 9999)
    {
        return intval(rand($min, $max));
    }

    public static function id()
    {
        if (isset($_SERVER['HTTP_X_USER'])) {
            $user_id = $_SERVER['HTTP_X_USER'];
        } elseif (isset($_COOKIE['user'])) {
            $user_id = $_COOKIE['user'];
        } else {
            $token = htmlspecialchars(self::token());

            self::$db = new Connect();
            self::$db = self::$db->connect();

            $sql = "SELECT `user_id` FROM `sessions` WHERE `token` = '$token';";

            $query = self::$db->query($sql);

            $query->execute();

            $user_id = $query->fetch();
            $user_id = $user_id->user_id;
        }
        return $user_id;
    }

    public static function user($id = null)
    {
        $request = json_decode(file_get_contents('php://input'), true);

        if (!isset($_GET['id']) && is_null($id)) {
            if (isset($_SERVER['HTTP_X_USER'])) {
                $user_id = $_SERVER['HTTP_X_USER'];
            } elseif (isset($_COOKIE['user'])) {
                $user_id = $_COOKIE['user'];
            }
        } else {
            if (isset($_GET['id'])) {
                $user_id = $_GET['id'];
            }
            if (!is_null($id)) {
                $user_id = $id;
            }
        }

        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = trim($user_id);

        $query = self::$db->prepare("SELECT `id`, `username`,`full_name`, `email`, `phone`, `otp`, `role` FROM `users` WHERE `id` = :user;");

        $query->bindParam(':user', $user, \PDO::PARAM_INT);

        $query->execute();

        $result = $query->fetch();

        $meta = Users::get_all_meta($user);

        $result->meta = $meta;

        return $result;
    }

    public static function token()
    {
        $headers = getallheaders();

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_COOKIE['token'])) {
            return $_COOKIE['token'];
        } elseif ($headers["Authorization"]) {
            return $headers["Authorization"];
        } else {
            return '';
        }
    }



    public static function roles()
    {

        self::$db = new Connect();
        self::$db = self::$db->connect();

        $query = self::$db->prepare("SELECT `id`, `role_code`,`role_name` FROM `user_roles`;");

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    public static function camel_to_snake($camel)
    {
        $snake = preg_replace('/[A-Z]/', '_$0', $camel);
        $snake = strtolower($snake);
        $snake = ltrim($snake, '_');
        return $snake;
    }

    public static function snake_to_camel($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $str[0] = strtolower($str[0]);
        return $str;
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public static function distanceBetween(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
