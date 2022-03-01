<?php


namespace Jara\Core\Helpers;

use Jara\Core\Database\Connect;

class Session
{
    public static $db;
    public static $maximal;

    public static function update_last_seen($id)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $id = strtolower(trim($id));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("UPDATE `users` SET `last_seen` = :datetime WHERE `id` = :id;");

        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Roles allowed to access this route
     *
     * @param array $allowed_roles array of role codes (default 1 to 6)
     *
     * @return void 
     */
    public static function check($allowed_roles = [0, 1, 2, 3, 4, 5, 6])
    {

        $token = Essentials::token();
        $user_id = Essentials::id();
        self::$db = new Connect();
        self::$db = self::$db->connect();


        $query = self::$db->prepare("SELECT `user_id`, (SELECT `role` FROM `users` WHERE `id` = `sessions`.`user_id`) AS `role` FROM `sessions` WHERE token = :token;");

        $query->bindParam(':token', $token, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch();

        if ($result && ($result->user_id == $user_id) && in_array($result->role, $allowed_roles)) {
            self::update_last_seen($user_id);
            return true;
        } else {
            echo  Response::new(401);
            exit;
        }
    }
}
