<?php

namespace Jara\Core\Helpers;

use Jara\Core\Database\Connect;
use stdClass;

class Setting
{
    public static $db;
    public static $maximal;

    public static function set($key, $value)
    {

        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("INSERT INTO `settings`(`setting_key`, `setting_value`, `datetime`)
                                        VALUES ( :setting_key, :setting_value, :datetime );");

        $query->bindParam(':setting_key', $key, \PDO::PARAM_STR);
        $query->bindParam(':setting_value', $value, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);

        return $query->execute();
    }

    public static function get($key, $defaultValue = null)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));

        $query = self::$db->prepare("SELECT `id`, `setting_value` AS `value`, datetime FROM `settings` WHERE `setting_key` = :setting_key");

        $query->bindParam(':setting_key', $key, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return $result;
        } else {
            $result = new stdClass();
            $result->id = 0;
            $result->value = $defaultValue;
            $result->datetime = Essentials::datetime();
            return $result;
        }
    }



    public static function get_all()
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $query = self::$db->prepare("SELECT `id`, `setting_value` AS `value`, `setting_key` AS `key`, datetime FROM `settings`");

        $query->execute();

        $result = $query->fetchAll();

        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    public static function update($key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("UPDATE `settings` SET `setting_value` = :setting_value, `datetime` = :datetime WHERE `setting_key` = :setting_key;");

        $query->bindParam(':setting_key', $key, \PDO::PARAM_STR);
        $query->bindParam(':setting_value', $value, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);

        return $query->execute();
    }

    public static function remove($key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));

        $query = self::$db->prepare("DELETE FROM `settings` WHERE `setting_key` = :setting_key");

        $query->bindParam(':setting_key', $key, \PDO::PARAM_STR);

        return $query->execute();
    }
}
