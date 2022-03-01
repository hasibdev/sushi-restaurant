<?php

namespace Jara\App\Models;

use Jara\App\Configs\Config;
use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Posts;
use Stripe\StripeClient;

class CheckoutModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }



    public function get_orders($page)
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();
        $page_no = intval($page[0]) - 1;
        $limit = 10;
        $offset = $page_no * $limit;

        if (isset($_GET['count'])) {
            if (isset($_GET['complete'])) {
                $text = '"status":"complete"';
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` LIKE '%$text%' ;");
            } elseif (isset($_GET['pos'])) {
                $text = '"deliveryTime":"Instant"';
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` LIKE '%$text%' ;");
            } elseif (isset($_GET['web'])) {
                $text = '"deliveryTime":"Instant"';
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` NOT LIKE '%$text%' ;");
            } else {
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' ;");
            }

            $query->execute();

            return $query->rowCount();
        } else {
            if (isset($_GET['complete'])) {
                $text = '"status":"complete"';
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` LIKE '%$text%' ORDER BY `id` DESC LIMIT :limit OFFSET :offset;");
            } elseif (isset($_GET['pos'])) {
                $text = '"deliveryTime":"Instant"';
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` LIKE '%$text%' ORDER BY `id` DESC LIMIT :limit OFFSET :offset;");
            } elseif (isset($_GET['web'])) {
                $text = '"deliveryTime":"Instant"';
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` NOT LIKE '%$text%' ORDER BY `id` DESC LIMIT :limit OFFSET :offset;");
            } else {
                $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' ORDER BY `id` DESC LIMIT :limit OFFSET :offset;");
            }
            $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $query->bindParam(':offset', $offset, \PDO::PARAM_INT);

            $query->execute();

            $results = $query->fetchAll();

            foreach ($results as $result) {

                $result->content = json_decode($result->content);
                $items = $result->content->items;

                foreach ($items as $item) {
                    $product = Posts::get_post($item->id);
                    $item->name = isset($product->name) ? $product->name : '';
                    $item->description = isset($product->description) ? $product->description : '';
                }
            }

            return $results;
        }
    }


    public function get_user_orders($id, $page)
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
        $page_no = intval($page) - 1;
        $limit = 10;
        $offset = $page_no * $limit;

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `user_id` = :id ORDER BY `id` DESC LIMIT :limit OFFSET :offset;");

        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $query->execute();

        if (isset($_GET['count'])) {
            return ceil($query->rowCount() / $limit);
        } else {
            $results = $query->fetchAll();

            foreach ($results as $result) {

                $result->content = json_decode($result->content);
                $items = $result->content->items;

                foreach ($items as $item) {
                    $product = Posts::get_post($item->id);
                    $item->name = $product->title;
                    $item->description = $product->description;
                }
            }
            return $results;
        }
    }


    public function get_pending_orders($page)
    {

        $this->db = new Connect();
        $this->db = $this->db->connect();
        $page_no = intval($page[0]) - 1;
        $limit = 20;
        $offset = $page_no * $limit;

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_type` = 'orders' AND `content` LIKE '%pending%' ORDER BY `id` DESC LIMIT :limit OFFSET :offset;");

        $query->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $query->execute();

        if (isset($_GET['count'])) {
            return ceil($query->rowCount() / $limit);
        } else {
            $results = $query->fetchAll();

            foreach ($results as $result) {

                $result->content = json_decode($result->content);
                $items = $result->content->items;

                foreach ($items as $item) {
                    $product = Posts::get_post($item->id);
                    $item->name = $product->title;
                    $item->description = $product->description;
                }
            }

            return $results;
        }
    }

    public function get_order($id)
    {
        $id = $id[0];

        $this->db = new Connect();
        $this->db = $this->db->connect();

        $query = $this->db->prepare("SELECT * FROM `posts` WHERE `id` = :id AND `post_type` = 'orders'");

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch();

        $result->content = json_decode($result->content);

        $items = $result->content->items;

        foreach ($items as $item) {
            $product = Posts::get_post($item->id);
            $item->name = $product->title;
            $item->description = $product->description;
        }

        return $result;
    }

    // stripe payment 
    public function stripe_pay($amount, $description, $token, $currency)
    {
        $amount = floatval($amount) * 100;
        $description = trim($description);
        $token = trim($token);
        $currency = substr(strtolower($currency), 0, 3);

        $stripe = new StripeClient(
            Config::$stripe_key
        );
        $result = $stripe->charges->create([
            'amount' => $amount,
            'currency' => $currency,
            'source' => $token,
            'description' => $description,
        ]);

        return $result;
    }

    public function update_status($id, $status)
    {
        $order = $this->get_order([$id]);
        $order = $order->content;
        $order->status = $status;

        return Posts::update($id, '', '', json_encode($order), 'orders', 0, 1, 'text/plain', '');
    }

    public function get_tokens()
    {
        $query = $this->db->query("SELECT `meta_value` FROM `user_meta` WHERE `meta_key` = 'push_token'");
        $query->execute();

        return $query->fetchAll();
    }
}
