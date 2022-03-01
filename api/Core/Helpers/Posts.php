<?php

namespace Jara\Core\Helpers;

use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Opuu\Maximal;

class Posts
{
    public static $db;

    /**
     * Add post to database
     *
     * @param integer $user_id post author id
     * @param string $title post title (Nullable)
     * @param string $desc post description (Nullable)
     * @param string $content post content 
     * @param string $post_type post type (this will be used to determine what data the post contains)
     * @param boolean $comments allowd user content? (default: false)
     * @param boolean $visible is it visible in public api (default: true)
     * @param string $mime_type mime type of the content (defautl: text/plain)
     * @param string $slug
     *
     * @return void
     */
    public static function add($user_id, $title, $desc, $content, $post_type, $comments = 0, $visible = 1, $mime_type = 'text/plain', $slug = null)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $datetime = Essentials::datetime();

        $modified = Essentials::datetime();

        $id = new Maximal('JaraCMS');

        $guid = $id->GUID();

        $title = trim(ucwords($title));
        $desc = trim($desc);
        $post_type = trim(strtolower($post_type));

        if (!$slug) {
            $slug = $title;
            $slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $slug))))), '-'));
        }

        $sql = "INSERT INTO `posts` (
            `id`,
            `user_id`,
            `title`,
            `description`,
            `content`,
            `post_type`,
            `visible`,
            `comments`,
            `slug`,
            `datetime`,
            `modified`,
            `mime_type`,
            `guid`
        ) VALUES (
            NULL,
            :user_id,
            :title,
            :description,
            :content,
            :post_type,
            :visible,
            :comments,
            :slug,
            :datetime,
            :modified,
            :mime_type,
            :guid
        );";

        $query = self::$db->prepare($sql);

        $query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $query->bindParam(':title', $title, \PDO::PARAM_STR);
        $query->bindParam(':description', $desc, \PDO::PARAM_STR);
        $query->bindParam(':content', $content, \PDO::PARAM_STR);
        $query->bindParam(':post_type', $post_type, \PDO::PARAM_STR);
        $query->bindParam(':visible', $visible, \PDO::PARAM_BOOL);
        $query->bindParam(':comments', $comments, \PDO::PARAM_BOOL);
        $query->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        $query->bindParam(':modified', $modified, \PDO::PARAM_STR);
        $query->bindParam(':mime_type', $mime_type, \PDO::PARAM_STR);
        $query->bindParam(':guid', $guid, \PDO::PARAM_STR);

        $query->execute();


        $id = self::$db->lastInsertId();
        return $id;
    }

    /**
     * Remove user from database
     *
     * @param string|integer $id
     *
     * @return void row count
     */
    public static function remove($id)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $id = trim($id);

        $query = self::$db->prepare("DELETE FROM `posts` WHERE `id` = :id OR  `guid` = :id;");

        $query->bindParam(':id', $id, \PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount();
    }

    public static function update($id,  $title, $description, $content, $post_type, $comments, $visible, $mime_type, $slug)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $modified = Essentials::datetime();

        $title = trim(ucwords($title));
        $description = trim($description);

        if (!$slug) {
            $slug = $title;
            $slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $slug))))), '-'));
        }

        $sql = "UPDATE `posts` SET
            `title` = :title,
            `description` = :description,
            `content` = :content,
            `post_type` = :post_type,
            `visible` = :visible,
            `comments` = :comments,
            `slug` = :slug,
            `modified` = :modified,
            `mime_type` = :mime_type
            WHERE `id` = :id;";

        $query = self::$db->prepare($sql);

        $query->bindParam(':title', $title, \PDO::PARAM_STR);
        $query->bindParam(':description', $description, \PDO::PARAM_STR);
        $query->bindParam(':content', $content, \PDO::PARAM_STR);
        $query->bindParam(':post_type', $post_type, \PDO::PARAM_STR);
        $query->bindParam(':visible', $visible, \PDO::PARAM_BOOL);
        $query->bindParam(':comments', $comments, \PDO::PARAM_BOOL);
        $query->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $query->bindParam(':modified', $modified, \PDO::PARAM_STR);
        $query->bindParam(':mime_type', $mime_type, \PDO::PARAM_STR);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Get post
     *
     * @param integer $post post id
     * @param string $key post meta key or identifier
     *
     * @return object stdObject of post meta
     */
    public static function get_post($post)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $post = trim($post);

        $query = self::$db->prepare("SELECT * FROM `posts` WHERE `id` = :post OR `guid` = :post");

        $query->bindParam(':post', $post, \PDO::PARAM_INT);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }


    /**
     * Get user meta
     *
     * @param integer $user user id
     * @param string $key user meta key or identifier
     *
     * @return object stdObject of user meta
     */
    public static function get_meta($post, $key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $post = trim($post);

        $query = self::$db->prepare("SELECT `id`, `meta_key` AS `key`,`meta_value` AS `value`, datetime FROM `post_meta` WHERE `post_id` = :post AND `meta_key` = :key");

        $query->bindParam(':post', $post, \PDO::PARAM_INT);
        $query->bindParam(':key', $key, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }


    /**
     * Get all meta for specific post
     *
     * @param integer $post post id
     *
     * @return array array of post meta stdObjects
     */
    public static function get_all_meta($post)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $post = trim($post);

        $query = self::$db->prepare("SELECT `id`, `meta_key` AS `key`,`meta_value` AS `value`, datetime FROM `post_meta` WHERE `post_id` = :post");

        $query->bindParam(':post', $post, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Set post meta
     *
     * @param integer $post post if
     * @param string $key meta identifier
     * @param string $value meta value
     *
     * @return void
     */
    public static function set_meta($post, $key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $post = trim($post);
        $key = strtolower(trim($key));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("INSERT INTO `post_meta`(`post_id`, `meta_key`, `meta_value`, `datetime`)
                                        VALUES ( :post_id, :meta_key, :meta_value, :datetime );");

        $query->bindParam(':post_id', $post, \PDO::PARAM_STR);
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
     * Remove post meta
     *
     * @param integer $post post id
     * @param string $key meta identifier
     *
     * @return void
     */
    public static function remove_meta($post, $key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $post = trim($post);

        $query = self::$db->prepare("DELETE FROM `post_meta` WHERE `meta_key` = :key AND `post_id` = :post");

        $query->bindParam(':key', $key, \PDO::PARAM_STR);
        $query->bindParam(':post', $post, \PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Update post meta
     *
     * @param integer $post post id
     * @param string $key meta identifier
     * @param string $value meta value
     *
     * @return void
     */
    public static function update_meta($post, $key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $post = trim($post);
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("UPDATE `post_meta` SET `meta_value` = :meta_value, `datetime` = :datetime WHERE `meta_key` = :meta_key AND `post_id` = :post_id;");

        $query->bindParam(':meta_value', $value, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        $query->bindParam(':meta_key', $key, \PDO::PARAM_STR);
        $query->bindParam(':post_id', $post, \PDO::PARAM_STR);

        return $query->execute();
    }
}
