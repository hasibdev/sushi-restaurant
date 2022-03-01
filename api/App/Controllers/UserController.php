<?php

namespace Jara\App\Controllers;

use Jara\App\Configs\Config;
use Jara\App\Models\UserModel;
use Jara\Core\Helpers\Email;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;
use Jara\Core\Helpers\Users;

class UserController
{
    public $model;

    function __construct()
    {
        $this->model = new UserModel();
    }

    public function get_all_users()
    {
        Session::check([0, 1, 2, 3, 4, 5]);

        return Response::new(200, $this->model->get_all_users());
    }

    public function get_user($id = null)
    {
        Session::check();

        if ($id == null) {
            $id = Essentials::id();
        }

        return Response::new(200, $this->model->get_user($id));
    }

    public function add_user()
    {
        Session::check([0, 1, 2, 3, 4]);

        $request = Request::require('username', 'email', 'name', 'password', 'phone', 'role', 'image');
        $errors = [];

        $request['phone'] = str_replace([' ', '.', '-', '(', ')', '+'], '', $request['phone']);

        // validate phone
        if (preg_match('/[A-Za-z]/', $request['phone'])) {
            $errors['phone'] = 'Invalid phone number.';
        }

        if ($this->model->is_unique_phone($request['phone'])) {
            $errors['phone'] = 'This phone number is already in use.';
        }

        // validate username
        if (!preg_match('/^[a-zA-Z.\d]+$/', $request['username'])) {
            $errors['username'] = 'Username can only contain letters and numbers.';
        }

        if ($this->model->is_unique_username($request['username'])) {
            $errors['username'] = 'Username already taken.';
        }

        // validate email
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        }

        if ($this->model->is_unique_email($request['email'])) {
            $errors['email'] = 'This email address is already in use.';
        }


        // validate name
        if (!preg_match('/[A-Za-z. ]$/', $request['name'])) {
            $errors['name'] = 'Name can only contain letters.';
        }

        // validate password
        if (strlen($request['password']) < 7) {
            $errors['password'] = 'Password must be atleast 8 characters long.';
        }

        if (!$request['role']) {
            $request['role'] = 5;
        }

        if ($errors !== []) {
            return Response::new(400, ['error' => $errors]);
        } else {
            $id = $this->model->add_user(
                $request['username'],
                $request['email'],
                $request['name'],
                $request['password'],
                $request['phone'],
                $request['role']
            );
            if (strlen($request['image']) > 5) {
                $image = Images::add($request['image'], 60, 60, 'webp');
                Users::set_meta($id, 'user_image', $image);
            }


            foreach ($request['meta'] as $key => $value) {
                Users::set_meta($id, $key, $value);
            }

            $user = $this->model->get_user($id);

            $html = file_get_contents(BASEPATH . '/Core/Helpers/Templates/Email/email.template');

            $html = str_replace('{{color}}', Config::$app_theme_color, $html);
            $html = str_replace('{{title}}', 'You have been added to ' . Config::$app_name . '.', $html);
            $html = str_replace('{{text}}', 'You have been added to ' . Config::$app_name . ' as an ' . $user->role . '. Your password is ' . $request['password'] . '.', $html);
            $html = str_replace('{{action_link}}', Config::$app_url, $html);
            $html = str_replace('{{action_text}}', 'Login', $html);

            Email::send(
                $request['email'],
                $request['name'],
                'You have been added to ' . Config::$app_name . '.',
                $html,
                'You have been added to ' . Config::$app_name . ' as an ' . $user->role . '. Your password is ' . $request['password'] . '. You can login to your account from ' . Config::$app_url . '.',
            );

            return Response::new(200, $id);
        }
    }

    public function search_user($string)
    {
        Session::check([0, 1, 2, 3, 4]);
        $results = $this->model->search_user($string);
        foreach ($results as $result) {
            $result->street = Users::get_meta($result->id, 'address')->value;
            $result->index = $result->full_name . ' ' . $result->street . ' ' . $result->phone . ' ' . $result->email . ' ' . $result->username;
        }
        return json_encode($results);
    }

    public function remove_user()
    {
        $request = Request::require('id');

        Session::check([0, 1, 2]);

        $my_role = Essentials::user()->role;
        $user_role = Essentials::user($request['id'])->role;

        if ($user_role > $my_role) {

            $name = Users::get_meta(Essentials::user($request['id'])->id, 'image');

            if ($name->value) {
                Images::remove($name->value);
            }

            return    Response::new(200, $this->model->remove_user($request['id']));
        } else {
            return Response::new(401);
        }
    }

    public function update_user()
    {
        Session::check([0, 1, 2, 3, 4, 5]);
        $request = Request::require('id', 'username', 'phone', 'name', 'email', 'meta');


        if (!$request['role']) {
            $request['role'] = 5;
        }

        $my_role = Essentials::user()->role;
        $user_role = Essentials::user($request['id'])->role;

        $errors = [];

        if ($user_role >= $my_role && $user_role != 0) {


            $request['phone'] = str_replace([' ', '.', '-', '(', ')', '+'], '', $request['phone']);

            // validate phone
            if (preg_match('/[A-Za-z]/', $request['phone'])) {
                $errors['phone'] = 'Invalid phone number.';
            }

            // validate username
            if (!preg_match('/^[a-zA-Z.\d]+$/', $request['username'])) {
                $errors['username'] = 'Username can only contain letters and numbers.';
            }


            // validate email
            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email address.';
            }

            // validate name
            if (!preg_match('/[A-Za-z. ]$/', $request['name'])) {
                $errors['name'] = 'Name can only contain letters.';
            }


            if ($user_role == $my_role) {
                $request['role'] = $my_role;
            }


            if ($errors !== []) {
                return Response::new(400, ['error' => $errors]);
            } else {
                if (strlen(trim($request['image']))) {

                    $path = Images::add(
                        trim($request['image']),
                        300,
                        300,
                        'webp'
                    );

                    $old = Users::get_meta($request['id'], 'image');
                    if (strlen($old->value)) {
                        Users::update_meta($request['id'], 'image', $path);
                        Images::remove($old->value);
                    } else {
                        Users::set_meta($request['id'], 'image', $path);
                    }

                    if (strlen($request['address']) > 0) {
                        Users::update_meta($request['id'], 'address', $request['address']);
                    }
                }

                foreach ($request['meta'] as $key => $value) {
                    $old = Users::get_meta($request['id'], $key);

                    if (isset($old->value)) {
                        Users::update_meta($request['id'], $key, $value);
                    } else {
                        Users::set_meta($request['id'], $key, $value);
                    }
                }

                return Response::new(200, $this->model->update_user(
                    $request['id'],
                    $request['name'],
                    $request['username'],
                    $request['email'],
                    $request['phone'],
                    $request['role']
                ));
            }
        } else {
            return Response::new(400);
        }
    }
}
