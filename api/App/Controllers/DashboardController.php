<?php

namespace Jara\App\Controllers;

use DateTime;
use Jara\App\Models\DashboardModel;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Session;

class DashboardController
{
    public $model;

    function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function get_overview($year)
    {
        Session::check([0, 1, 2, 3, 4]);

        $all_orders = $this->model->get_all_orders($year);
        $orders = [];

        foreach ($all_orders as $order) {
            $content = json_decode($order->content);
            $content->id = $order->id;
            $content->datetime = $order->datetime;
            $orders[] = $content;
        }

        $all_orders = null;

        $total_orders = count($orders);
        $total_amount = 0;
        $total_products = 0;
        $total_additions = 0;
        $best_seller = [];
        $pending_orders = 0;
        $complete_orders = 0;
        $cancelled_orders = 0;
        $out_for_delivery = 0;
        $paid_orders = 0;
        $processing_orders = 0;

        $months = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0
        ];

        foreach ($orders as $order) {
            // print_r($order);
            $is_cod = $order->paymentMethod == 'CASH' ? true : false;
            if (!$is_cod) {
                $paid_orders++;
            }


            if (!isset($order->status)) {
                $order->status = 'pending';
            }


            switch ($order->status) {
                case 'pending':
                    $pending_orders++;
                    break;
                case 'processing':
                    $processing_orders++;
                    break;
                case 'complete':
                    $complete_orders++;
                    if ($is_cod) {
                        $paid_orders++;
                    }
                    break;
                case 'cancelled':
                    $cancelled_orders++;
                    break;
                case 'out_for_delivery':
                    $out_for_delivery++;
                    break;
                default:
                    $complete_orders++;
                    if ($is_cod) {
                        $paid_orders++;
                    }
                    break;
            }

            foreach ($order->items as $item) {
                if ($order->status != 'cancelled') {
                    $best_seller[] += $item->id;
                    $total_products += 1;
                    if (isset($item->total)) {
                        $total_amount += intval($item->total);
                    }
                    $total_additions += $item->addition_price ?? 0;
                }
            }


            if ($order->status != 'cancelled') {
                $months[date('F', strtotime($order->datetime))] += $order->total;
            }
        }

        $best_seller = array_count_values($best_seller);
        arsort($best_seller);

        $array_keys = array_keys($best_seller);
        $array_values = array_values($best_seller);

        $popular = [];

        foreach ($array_keys as $key => $value) {
            if ($key >= 5) break;

            $post = Posts::get_post($value);

            $result = new \stdClass();
            $result->sold = floatval($array_values[$key]);
            $result->total_value = round(floatval($array_values[$key]) * floatval($post->content), 2);
            $result->name = $post->title;
            $result->id = intval($post->id);
            $result->price = floatval($post->content);
            $popular[] = $result;
            $post = null;
        }
        $newMonths = [];
        foreach ($months as $key => $month) {
            $newMonths[$key] = round(floatval($month), 2);
        }
        $months = $newMonths;

        $result = [
            'best_seller' => $popular,
            'total_orders' => floatval($total_orders),
            'total_amount' => floatval($total_amount),
            'total_products' => floatval($total_products),
            'total_additions' => floatval($total_additions),
            'pending_orders' => floatval($pending_orders),
            'complete_orders' => floatval($complete_orders),
            'cancelled_orders' => floatval($cancelled_orders),
            'out_for_delivery' => floatval($out_for_delivery),
            'paid_orders' => floatval($paid_orders),
            'processing_orders' => floatval($processing_orders),
            'monthly_amount' => $months
        ];

        // free the memory
        $best_seller = null;
        $array_keys = null;
        $array_values = null;
        $popular = null;
        $orders = null;
        $total_additions = null;
        $total_amount = null;
        $total_products = null;
        $total_orders = null;


        return json_encode($result);
    }

    public function get_report()
    {
        Session::check([0, 1, 2, 3, 4]);

        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;

        if (!$start || !$end) {
            $start = date('Y-m-d H:i:s', strtotime('-1 month'));
            $end = date('Y-m-d H:i:s');
        }

        if ($start == $end) {
            $end = date('Y-m-d H:i:s', strtotime('+1 day'));
        }

        $start = new DateTime($start);
        $end = new DateTime($end);


        $start = $start->format('Y-m-d H:i:s');
        $end = $end->format('Y-m-d H:i:s');

        $all_orders = $this->model->get_order_by_date_range($start, $end);

        $orders = [];

        foreach ($all_orders as $order) {
            $content = json_decode($order->content);
            $content->id = $order->id;
            $content->datetime = $order->datetime;
            $orders[] = $content;
        }

        $all_orders = null;

        $total_orders = count($orders);
        $total_amount = 0;
        $total_products = 0;
        $total_additions = 0;
        $best_seller = [];
        $pending_orders = 0;
        $complete_orders = 0;
        $cancelled_orders = 0;
        $out_for_delivery = 0;
        $paid_orders = 0;
        $processing_orders = 0;

        foreach ($orders as $order) {
            // print_r($order);
            $is_cod = $order->paymentMethod == 'CASH' ? true : false;
            if (!$is_cod) {
                $paid_orders++;
            }

            if (!isset($order->status)) {
                $order->status = 'pending';
            }

            switch ($order->status) {
                case 'pending':
                    $pending_orders++;
                    break;
                case 'processing':
                    $processing_orders++;
                    break;
                case 'complete':
                    $complete_orders++;
                    if ($is_cod) {
                        $paid_orders++;
                    }
                    break;
                case 'cancelled':
                    $cancelled_orders++;
                    break;
                case 'out_for_delivery':
                    $out_for_delivery++;
                    break;
                default:
                    $complete_orders++;
                    if ($is_cod) {
                        $paid_orders++;
                    }
                    break;
            }

            foreach ($order->items as $item) {
                if ($order->status != 'cancelled') {
                    $best_seller[] += $item->id;
                    $total_products += 1;
                    if (isset($item->total)) {
                        $total_amount += intval($item->total);
                    }
                    $total_additions += $item->addition_price ?? 0;
                }
            }
        }

        $best_seller = array_count_values($best_seller);
        arsort($best_seller);

        $array_keys = array_keys($best_seller);
        $array_values = array_values($best_seller);

        $popular = [];

        foreach ($array_keys as $key => $value) {
            if ($key >= 5) break;

            $post = Posts::get_post($value);

            $result = new \stdClass();
            $result->sold = floatval($array_values[$key]);
            $result->total_value = round(floatval($array_values[$key]) * floatval($post->content), 2);
            $result->name = $post->title;
            $result->id = intval($post->id);
            $result->price = floatval($post->content);
            $popular[] = $result;
            $post = null;
        }

        $result = [
            'best_seller' => $popular,
            'total_orders' => floatval($total_orders),
            'total_amount' => floatval($total_amount),
            'total_products' => floatval($total_products),
            'total_additions' => floatval($total_additions),
            'pending_orders' => floatval($pending_orders),
            'complete_orders' => floatval($complete_orders),
            'cancelled_orders' => floatval($cancelled_orders),
            'out_for_delivery' => floatval($out_for_delivery),
            'paid_orders' => floatval($paid_orders),
            'processing_orders' => floatval($processing_orders),
            'all_orders' => $orders
        ];

        // free the memory
        $best_seller = null;
        $array_keys = null;
        $array_values = null;
        $popular = null;
        $orders = null;
        $total_additions = null;
        $total_amount = null;
        $total_products = null;
        $total_orders = null;


        return json_encode($result);
    }
}
