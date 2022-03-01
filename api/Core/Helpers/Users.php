<?php

namespace Jara\Core\Helpers;

use Jara\Core\Database\Connect;

/**
 * Manage Users
 */
class Users
{

    public static $db;
    public static $maximal;

    /**
     * Create new user
     *
     * @param string $username Unique user name for user
     * @param string $email Unique email for user
     * @param string $full_name Full name of user
     * @param string $password Password to set for user
     * @param string|integer $phone Phone number of user
     * @param integer $role User role
     *
     * @return void returns user id
     */
    public static function add($username, $email, $full_name, $password, $phone, $role = 5)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $password = password_hash($password, PASSWORD_BCRYPT);
        $full_name = ucwords(trim($full_name));
        $phone = preg_replace('/[^0-9]/', '', trim($phone));
        $email = strtolower(trim($email));
        $username = strtolower(trim($username));
        $datetime = Essentials::datetime();
        $last_seen = Essentials::datetime();


        $query = self::$db->prepare("INSERT INTO `users`(`username`, `full_name`, `email`, `password`, `phone`, `otp`, `role`, `datetime`, `last_seen`)
                                        VALUES ( :username , :full_name , :email , :password , :phone , NULL , :role , :datetime , :last_seen );");

        $query->bindParam(':username', $username, \PDO::PARAM_STR);
        $query->bindParam(':full_name', $full_name, \PDO::PARAM_STR);
        $query->bindParam(':email', $email, \PDO::PARAM_STR);
        $query->bindParam(':password', $password, \PDO::PARAM_STR);
        $query->bindParam(':phone', $phone, \PDO::PARAM_STR);
        $query->bindParam(':role', $role, \PDO::PARAM_INT);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        $query->bindParam(':last_seen', $last_seen, \PDO::PARAM_STR);
        $query->execute();
        $id = self::$db->lastInsertId();
        return $id;
    }

    /**
     * Remove user
     *
     * @param integer|string $user username, user id, user phone number or user email
     *
     * @return void
     */
    public static function remove($user)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));

        $query = self::$db->prepare("DELETE FROM `users` WHERE `id` = :user OR  `username` = :user OR  `email` = :user;");

        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount();
    }

    /**
     * Update user role
     * 
     * Make sure to check for permission before changing
     *
     * @param integer|string $user username, user id, user phone number or user email
     * @param integer $role role code
     *
     * @return void
     */
    public static function set_role($user, $role = 5)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));

        $query = self::$db->prepare("UPDATE `users` SET `role` = :role WHERE `id` = :user OR  `username` = :user OR  `email` = :user;");

        $query->bindParam(':role', $role, \PDO::PARAM_INT);
        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        return $query->execute();
    }

    /**
     * Update user full name
     *
     * @param integer|string $user username, user id, user phone number or user email
     * @param string $full_name Full name
     *
     * @return void
     */
    public static function set_full_name($user, $full_name)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));
        $full_name = ucwords(trim($full_name));

        $query = self::$db->prepare("UPDATE `users` SET `full_name` = :full_name WHERE `id` = :user OR  `username` = :user OR  `email` = :user;");

        $query->bindParam(':full_name', $full_name, \PDO::PARAM_STR);
        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        return $query->execute();
    }

    /**
     * Set new otp for user to send
     *
     * @param integer|string $user username, user id, user phone number or user email
     * @param integer $otp otp code  [Recommended lenght: 6]
     *
     * @return void
     */
    public static function set_otp($user, $otp)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));

        $query = self::$db->prepare("UPDATE `users` SET `otp` = :otp WHERE `email` = :user;");

        $query->bindParam(':otp', $otp, \PDO::PARAM_INT);
        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        return $query->execute();
    }



    /**
     * Set new phone number
     *
     * @param integer|string $user username, user id, user phone number or user email
     * @param integer $phone phone number must be uniqe
     *
     * @return void
     */
    public static function set_phone($user, $phone)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));

        $query = self::$db->prepare("UPDATE `users` SET `phone` = :phone WHERE `id` = :user OR  `username` = :user OR  `email` = :user;");

        $query->bindParam(':phone', $phone, \PDO::PARAM_INT);
        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        return $query->execute();
    }


    /**
     * Set new email
     *
     * @param integer|string $user username, user id, user phone number or user email
     * @param integer $email email must be uniqe
     *
     * @return void
     */
    public static function set_email($user, $email)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $query = self::$db->prepare("UPDATE `users` SET `email` = :email WHERE `id` = :user OR  `username` = :user OR  `email` = :user;");

        $query->bindParam(':email', $email, \PDO::PARAM_STR);
        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        return $query->execute();
    }




    /**
     * Set new username
     *
     * @param integer|string $user username, user id, user phone number or user email
     * @param integer $username username must be uniqe
     *
     * @return void
     */
    public static function set_username($user, $username)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $username = strtolower(trim($username));

        $query = self::$db->prepare("UPDATE `users` SET `username` = :username WHERE `id` = :user OR  `username` = :user OR  `email` = :user;");

        $query->bindParam(':username', $username, \PDO::PARAM_STR);
        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        return $query->execute();
    }

    /**
     * Get user meta
     *
     * @param integer $user user id
     * @param string $key user meta key or identifier
     *
     * @return object stdObject of user meta
     */
    public static function get_meta($user, $key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $user = strtolower(trim($user));

        $query = self::$db->prepare("SELECT `id`, `meta_key` AS `key`,`meta_value` AS `value`, datetime FROM `user_meta` WHERE `user_id` = :user AND `meta_key` = :key");

        $query->bindParam(':user', $user, \PDO::PARAM_INT);
        $query->bindParam(':key', $key, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }


    /**
     * Get all meta for specific user
     *
     * @param integer $user user id
     *
     * @return array array of user meta stdObjects
     */
    public static function get_all_meta($user)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));

        $query = self::$db->prepare("SELECT `id`, `meta_key` AS `key`,`meta_value` AS `value`, datetime FROM `user_meta` WHERE `user_id` = :user");

        $query->bindParam(':user', $user, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Set user meta
     *
     * @param integer $user user if
     * @param string $key meta identifier
     * @param string $value meta value
     *
     * @return void
     */
    public static function set_meta($user, $key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $user = strtolower(trim($user));
        $key = strtolower(trim($key));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("INSERT INTO `user_meta`(`user_id`, `meta_key`, `meta_value`, `datetime`)
                                        VALUES ( :user_id, :meta_key, :meta_value, :datetime );");

        $query->bindParam(':user_id', $user, \PDO::PARAM_STR);
        $query->bindParam(':meta_key', $key, \PDO::PARAM_STR);
        $query->bindParam(':meta_value', $value, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        try {
            $query->execute();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Remove user meta
     *
     * @param integer $user user id
     * @param string $key meta identifier
     *
     * @return void
     */
    public static function remove_meta($user, $key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $user = strtolower(trim($user));

        $query = self::$db->prepare("DELETE FROM `user_meta` WHERE `meta_key` = :key AND `user_id` = :user");

        $query->bindParam(':key', $key, \PDO::PARAM_STR);
        $query->bindParam(':user', $user, \PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Update user meta
     *
     * @param integer $user user id
     * @param string $key meta identifier
     * @param string $value meta value
     *
     * @return void
     */
    public static function update_meta($user, $key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $user = strtolower(trim($user));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("UPDATE `user_meta` SET `meta_value` = :meta_value, `datetime` = :datetime WHERE `meta_key` = :meta_key AND `user_id` = :user_id;");

        $query->bindParam(':meta_value', $value, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        $query->bindParam(':meta_key', $key, \PDO::PARAM_STR);
        $query->bindParam(':user_id', $user, \PDO::PARAM_STR);

        return $query->execute();
    }
}
