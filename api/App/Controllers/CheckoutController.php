<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\AuthModel;
use Jara\App\Models\CheckoutModel;
use Jara\App\Models\UserModel;
use Jara\Core\Helpers\Email;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Firebase;
use Jara\Core\Helpers\Opuu\Maximal;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Posts;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Session;
use Jara\Core\Helpers\Users;

class CheckoutController
{
    public $model;

    function __construct()
    {
        $this->model = new CheckoutModel();
        $this->authModel = new AuthModel();
        $this->userModel = new UserModel();
    }

    public function set_order()
    {
        $request = Request::require('object');


        // TODO: Refactor this code to make it cleaner
        try {

            // remove unecessary characters from phone number
            $request['object']['phone'] = str_replace([' ', '.', '-', '(', ')', '+'], '', $request['object']['phone']);

            $is_pos_order = isset($_GET['pos']);

            $customer_id = $request['object']['customerId'];

            if ($is_pos_order == true && $request['object']['userType'] == 'new' && strlen($request['object']['phone']) > 3) {
                $password = new Maximal('iWebDesigner');
                $password = $password->GUID();
                $userid = Users::add($request['object']['phone'], $request['object']['email'], $request['object']['name'], $password, $request['object']['phone'], 5);
                $request['object']['customerId'] = $userid;
                Users::set_meta($userid, 'address', $request['object']['street']);
            } elseif ($customer_id) {
                $userid = $customer_id;
            } else {
                $userid = null;
            }

            $id = Posts::add($userid, null, $request['object']['street'], json_encode($request['object']), 'orders');
            $total_cent = floatval($request['object']['total']) * 100;

            if (isset($request['object']['stripe'])) {
                $stripe = $this->model->stripe_pay($total_cent, "Payment of invoice #$id", $request['object']['stripe']['id'], $request['object']['currency']);
                $request['object']['stripe'] = $stripe;
            }


            $ids = $this->model->get_tokens();
            $new_ids = [];

            foreach ($ids as  $value) {
                $new_ids[] = $value->meta_value;
            }

            $ids = null;

            if (!$is_pos_order && isset($request['object']['email']) && !empty($request['object']['email'])) {

                // send user email
                ob_start();
                $emailTemplate = new EmailController();
                $emailTemplate->order_email([$id, false]);
                $output = ob_get_clean();

                $guid = Posts::get_post($id);

                $url = Config::$app_url . '/orders/' . $id . '/?key=' . $guid->guid;

                Email::send(
                    $request['object']['email'],
                    $request['object']['name'],
                    "Order #$id has been received.",
                    $output,
                    "Order #$id has been received successfully. To check it click $url.",
                );


                // send admin email

                // $url = Config::$app_url . '/orders/' . $id;
                $url = Config::$app_admin_url . "/orders/" . $id;

                Firebase::send_notification($new_ids, 'New order from ' . $request['object']['name'], "Order #$id has been received.", $url);


                $template = file_get_contents(BASEPATH . '/Core/Helpers/Templates/Email/email.template');

                $template = str_replace('{{color}}', Config::$app_theme_color, $template);
                $template = str_replace('{{title}}', "Order #$id has been received.", $template);
                $template = str_replace('{{text}}', "Hello, A new order order #$id has been successfully received.", $template);
                $template = str_replace('{{action_link}}', $url, $template);
                $template = str_replace('{{action_text}}', "Check Order", $template);

                Email::send(
                    Config::$admin_email,
                    "System Admin",
                    "New order #$id has been received.",
                    $template,
                    "Order #$id has been received successfully. To check it click $url.",
                );
            }

            $this->model->update_status($id, 'pending');

            if ($id) {
                return '{
                    "id": ' . $id . ',
                    "message": "We are processing it right now - your will receive it about 45 minutes..."
                  }';
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::new(400, 'Order could not be placed');
        }
    }

    public function update_status()
    {
        Session::check([0, 1, 2, 3, 4]);
        $request = Request::require('id', 'status');

        return $this->model->update_status($request['id'], $request['status']);
    }

    public function get_order($id)
    {
        Session::check([0, 1, 2, 3, 4]);
        return json_encode($this->model->get_order($id));
    }

    public function get_pending_orders($page)
    {
        Session::check([0, 1, 2, 3, 4]);
        return json_encode($this->model->get_pending_orders($page));
    }

    public function get_orders($page)
    {
        Session::check([0, 1, 2, 3, 4]);
        $results = $this->model->get_orders($page);

        return json_encode($results);
    }

    public function get_user_orders($page)
    {
        Session::check([5]);

        $page = $page[0];

        $id = Essentials::id();

        if ($id) {
            $results = $this->model->get_user_orders($id, $page);
            return Response::new(200, $results);
        } else {
            return Response::new(401);
        }
    }
}
