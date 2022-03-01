<?php

namespace Jara\App\Controllers;

use Jara\App\Models\AuthModel;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;
use Jara\Core\Helpers\Users;

class AuthController
{
    public $model;

    function __construct()
    {
        $this->model = new AuthModel();
    }

    public function verify_otp()
    {

        $this->model->email_otp('obayed.opu@gmail.com', 'Opu');
        $requset = Request::require('user', 'otp');

        if ($this->model->verify_otp($requset['user'], $requset['otp'])) {
            return Response::new(200, [
                'message' => 'Email verified successfully.',
            ]);
        } else {
            return Response::new(400, ['error' => ['Wrong otp.']]);
        }
    }

    public function signup()
    {
        $requset = Request::require('username', 'email', 'name', 'password', 'phone');

        $errors = [];

        $requset['phone'] = str_replace([' ', '.', '-', '(', ')', '+'], '', $requset['phone']);

        // validate phone
        if (preg_match('/[A-Za-z]/', $requset['phone'])) {
            $errors['phone'] = 'Invalid phone number 1';
        }

        if (!filter_var($requset['phone'], FILTER_VALIDATE_INT)) {
            $errors['phone'] = 'Invalid phone number.';
        }

        if ($this->model->is_unique_phone($requset['phone'])) {
            $errors['phone'] = 'This phone number is already in use.';
        }

        // validate username
        if (!preg_match('/^[a-zA-Z.\d]+$/', $requset['username'])) {
            $errors['username'] = 'Your username can only contain letters and numbers.';
        }

        if ($this->model->is_unique_username($requset['username'])) {
            $errors['username'] = 'Username already taken.';
        }

        // validate email
        if (!filter_var($requset['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        }

        if ($this->model->is_unique_email($requset['email'])) {
            $errors['email'] = 'This email address is already in use.';
        }


        // validate name
        if (!preg_match('/[A-Za-z .]$/', $requset['name'])) {
            $errors['name'] = 'Name can only contain letters.';
        }

        // validate password
        if (strlen($requset['password']) < 7) {
            $errors['password'] = 'Password must be atleast 8 characters long.';
        }

        if ($errors !== []) {
            return Response::new(400, ['error' => $errors]);
        } else {
            $id =  $this->model->create_user($requset['username'], $requset['email'], $requset['name'], $requset['password'], $requset['phone'], 5);
            $token = $this->model->new_session($id);

            if ($requset['address']) {
                Users::set_meta($id, 'address', $requset['address']);
            } else {
                Users::set_meta($id, 'address', '');
            }

            return Response::new(200, [
                'message' => 'User signed up successfully.',
                'id' => $id,
                'token' => $token
            ]);
        }
    }

    public function signin()
    {
        $requset = Request::require('user', 'password');

        $id = $this->model->verify_password($requset['user'], $requset['password']);
        if ($id) {
            $token = $this->model->new_session($id);

            return Response::new(200, [
                'message' => 'User signed in successfully.',
                'id' => $id,
                'token' => $token
            ]);
        } else {
            return Response::new(400, ['error' => ['Wrong credentials.']]);
        }
    }

    public function signout()
    {

        Session::check();

        $requset = Request::require('token');

        if ($this->model->delete_session($requset['token'])) {
            return Response::new(200, [
                'message' => 'User signed out successfully.',
            ]);
        } else {
            return Response::new(400, [
                'message' => 'User is not signed in.',
            ]);
        }
    }

    public function change_password()
    {
        Session::check();

        $user = Essentials::id();

        $requset = Request::require('user', 'password', 'new_password');

        if (intval($user) != intval($requset['user'])) {
            return Response::new(400, ['error' => ['Permission denied.']]);
        }

        $id = $this->model->verify_password($requset['user'], $requset['password']);

        if ($id) {
            $this->model->change_password($id, $requset['new_password']);
            return Response::new(200, [
                'message' => 'Password changed successfully.',
            ]);
        } else {
            return Response::new(400, ['error' => ['Wrong credentials.']]);
        }
    }

    public function forgot_password()
    {
        $requset = Request::require('email', 'otp', 'new_password');

        if (!filter_var($requset['email'], FILTER_VALIDATE_EMAIL)) {
            return Response::new(400, ['error' => ['Invalid email address.']]);
        }

        if (!$this->model->is_unique_email($requset['email'])) {
            return Response::new(400, ['error' => ['Email address not found.']]);
        }

        $isChanged = $this->model->forgot_password($requset['email'], $requset['otp'], $requset['new_password']);

        if ($isChanged) {
            Users::set_otp($requset['email'], null);
            return Response::new(200, [
                'message' => 'Password changed successfully.',
            ]);
        } else {
            return Response::new(400, ['error' => ['Wrong credentials.']]);
        }
    }

    public function send_otp()
    {
        $requset = Request::require('email');

        $this->model->send_otp($requset['email']);
        return Response::new(200);
    }
}
