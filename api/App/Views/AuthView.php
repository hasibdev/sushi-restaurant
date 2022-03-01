<?php

namespace Jara\App\Views;

use Jara\App\Controllers\AuthController;

class AuthView
{
    public $controller;

    function __construct()
    {
        $this->controller = new AuthController();
    }

    public function signup()
    {
        echo $this->controller->signup();
    }

    public function signin()
    {
        echo $this->controller->signin();
    }

    public function signout()
    {
        echo $this->controller->signout();
    }

    public function change_password()
    {
        echo $this->controller->change_password();
    }

    public function verify_otp()
    {
        echo $this->controller->verify_otp();
    }

    public function send_otp()
    {
        echo $this->controller->send_otp();
    }

    public function forgot_password()
    {
        echo $this->controller->forgot_password();
    }
}
