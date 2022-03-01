<?php

namespace Jara\Core\Helpers;

use Jara\Core\Database\Connect;


class Categories
{
    public static $db;


    public static function add($name, $desc, $slug = null, $parent = null)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $datetime = Essentials::datetime();

        $title = trim(ucwords($name));
        $desc = trim($desc);

        if (!$slug) {
            $slug = $title;
            $slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $slug))))), '-'));
        }

        $sql = "INSERT INTO `categories`(`id`,
         `parent`,
         `name`,
         `description`,
         `slug`,
         `datetime`) VALUES (NULL,
        :parent,
        :name,
        :description,
        :slug,
        :datetime);";

        $query = self::$db->prepare($sql);

        $query->bindParam(':parent', $parent, \PDO::PARAM_STR);
        $query->bindParam(':name', $title, \PDO::PARAM_STR);
        $query->bindParam(':description', $desc, \PDO::PARAM_STR);
        $query->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);

        $query->execute();

        $id = self::$db->lastInsertId();
        return $id;
    }

    /**
     * Remove category from database
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

        $query = self::$db->prepare("DELETE FROM `categories` WHERE `id` = :id;");

        $query->bindParam(':id', $id, \PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount();
    }


    public static function update($id, $name, $desc, $slug = null, $parent = null)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $datetime = Essentials::datetime();

        $title = trim(ucwords($name));
        $desc = trim($desc);

        if (!$slug) {
            $slug = $title;
            $slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $slug))))), '-'));
        }

        $sql = "UPDATE `categories` SET 
        `parent`=:parent,
        `name`=:name,
        `description`=:description,
        `slug`=:slug,
        `datetime`=:datetime 
        WHERE `id` = :id;";

        $query = self::$db->prepare($sql);

        $query->bindParam(':id', $id, \PDO::PARAM_STR);
        $query->bindParam(':parent', $parent, \PDO::PARAM_STR);
        $query->bindParam(':name', $title, \PDO::PARAM_STR);
        $query->bindParam(':description', $desc, \PDO::PARAM_STR);
        $query->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);

        return $query->execute();
    }


    /**
     * Get category meta
     *
     * @param integer $category category id
     * @param string $key category meta key or identifier
     *
     * @return object stdObject of category meta
     */
    public static function get_meta($category, $key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $category = trim($category);

        $query = self::$db->prepare("SELECT `id`, `meta_key` AS `key`,
        `meta_value` AS `value`, datetime FROM `category_meta` WHERE `category_id` = :category AND `meta_key` = :key");

        $query->bindParam(':category', $category, \PDO::PARAM_INT);
        $query->bindParam(':key', $key, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetch();

        return $result;
    }


    /**
     * Get all meta for specific category
     *
     * @param integer $category category id
     *
     * @return array array of category meta stdObjects
     */
    public static function get_all_meta($category)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $category = trim($category);

        $query = self::$db->prepare("SELECT `id`, `meta_key` AS `key`,`meta_value` AS `value`, datetime FROM `category_meta` WHERE `category_id` = :category");

        $query->bindParam(':category', $category, \PDO::PARAM_STR);

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    /**
     * Set category meta
     *
     * @param integer $category category if
     * @param string $key meta identifier
     * @param string $value meta value
     *
     * @return void
     */
    public static function set_meta($category, $key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $category = trim($category);
        $key = strtolower(trim($key));
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("INSERT INTO `category_meta`(`category_id`, `meta_key`, `meta_value`, `datetime`)
                                        VALUES ( :category_id, :meta_key, :meta_value, :datetime );");

        $query->bindParam(':category_id', $category, \PDO::PARAM_STR);
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
     * Remove category meta
     *
     * @param integer $category category id
     * @param string $key meta identifier
     *
     * @return void
     */
    public static function remove_meta($category, $key)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $category = trim($category);

        $query = self::$db->prepare("DELETE FROM `category_meta` WHERE `meta_key` = :key AND `category_id` = :category");

        $query->bindParam(':key', $key, \PDO::PARAM_STR);
        $query->bindParam(':category', $category, \PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Update category meta
     *
     * @param integer $category category id
     * @param string $key meta identifier
     * @param string $value meta value
     *
     * @return void
     */
    public static function update_meta($category, $key, $value)
    {
        self::$db = new Connect();
        self::$db = self::$db->connect();

        $key = strtolower(trim($key));
        $category = trim($category);
        $datetime = Essentials::datetime();

        $query = self::$db->prepare("UPDATE `category_meta` SET `meta_value` = :meta_value, `datetime` = :datetime WHERE `meta_key` = :meta_key AND `category_id` = :category_id;");

        $query->bindParam(':meta_value', $value, \PDO::PARAM_STR);
        $query->bindParam(':datetime', $datetime, \PDO::PARAM_STR);
        $query->bindParam(':meta_key', $key, \PDO::PARAM_STR);
        $query->bindParam(':category_id', $category, \PDO::PARAM_STR);

        return $query->execute();
    }
}
