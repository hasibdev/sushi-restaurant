<?php

namespace Jara\App\Models;

use Jara\App\Configs\Config;
use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Email;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Opuu\Maximal;
use Jara\Core\Helpers\Users;

class AuthModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }

    public function create_user($username, $email, $full_name, $password, $phone, $role = 5)
    {
        return Users::add($username, $email, $full_name, $password, $phone, $role = 5);
    }

    public function verify_password($id, $password)
    {
        $id = strtolower($id);

        $query = $this->db->prepare('SELECT `password`, `id` FROM `users` WHERE id = :id OR email = :email OR phone = :phone OR username = :username;');

        $query->bindParam(':id', $id);
        $query->bindParam(':email', $id);
        $query->bindParam(':phone', $id);
        $query->bindParam(':username', $id);

        $query->execute();

        $result = $query->fetch();

        if (password_verify($password, $result->password)) {
            return $result->id;
        } else {
            return false;
        }
    }

    public function verify_otp($user_id, $otp)
    {
        $id = strtolower($user_id);

        $query = $this->db->prepare('SELECT `otp`, `id` FROM `users` WHERE id = :id OR email = :email OR phone = :phone OR username = :username;');

        $query->bindParam(':id', $id);
        $query->bindParam(':email', $id);
        $query->bindParam(':phone', $id);
        $query->bindParam(':username', $id);

        $query->execute();

        $result = $query->fetch();

        if ($otp === $result->otp) {
            return $result->id;
        } else {
            return false;
        }
    }

    public function email_otp($email, $name)
    {
        $otp = Essentials::otp();
        Users::set_otp($email, $otp);
        $subject = 'Your one time password';
        $title = 'One time password';
        $text = 'Please use this one time password: ';
        $action_link = '#';
        $action_text = $otp;
        $color = Config::$app_theme_color;
        $email_temp = BASEPATH . '/Core/Helpers/Templates/Email/email.template';
        $template = file_get_contents($email_temp);

        $body = str_replace(
            [
                '{{title}}',
                '{{text}}',
                '{{action_text}}',
                '{{action_link}}',
                '{{color}}'
            ],
            [
                $title,
                $text,
                $action_text,
                $action_link,
                $color
            ],
            $template
        );

        $body_alt = "Your email verification code is : $otp";

        return Email::send($email, $name, $subject, $body, $body_alt);
    }

    public function new_session($user_id)
    {
        $datetime = Essentials::datetime();

        $token = new Maximal($user_id);
        $token = $token->GUID1();

        $query = $this->db->prepare('INSERT INTO `sessions`( `user_id`, `token`, `datetime`) VALUES (:id,:token,:datetime);');

        $query->bindParam(':id', $user_id);
        $query->bindParam(':token', $token);
        $query->bindParam(':datetime', $datetime);

        $query->execute();

        return $token;
    }

    public function delete_session($token)
    {
        $query = $this->db->prepare('DELETE FROM `sessions` WHERE `token` = :token;');

        $query->bindParam(':token', $token);

        $query->execute();

        return $query->rowCount();
    }

    public function is_unique_email($email)
    {

        $email = strtolower($email);

        $query = $this->db->prepare('SELECT `id` FROM `users` WHERE email = :email');

        $query->bindParam(':email', $email);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function is_unique_phone($phone)
    {

        $phone = preg_replace(
            '/[^0-9.]/',
            '',
            trim($phone)
        );

        $query = $this->db->prepare('SELECT `id` FROM `users` WHERE phone = :phone');

        $query->bindParam(':phone', $phone);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function is_unique_username($username)
    {

        $username = strtolower($username);

        $query = $this->db->prepare('SELECT `id` FROM `users` WHERE username = :username');

        $query->bindParam(':username', $username);

        $query->execute();

        $result = $query->fetch();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function change_password($id, $password)
    {
        $id = strtolower($id);
        $password = password_hash($password, PASSWORD_BCRYPT);

        if (strpos($id, '@') !== false) {
            $query = $this->db->prepare('UPDATE `users` SET `password` = :password WHERE `email` = :id;');
        } else {
            $query = $this->db->prepare('UPDATE `users` SET `password` = :password WHERE `id` = :id;');
        }


        $query->bindParam(':password', $password);
        $query->bindParam(':id', $id);

        $query->execute();

        return true;
    }

    public function send_otp($email)
    {
        return $this->email_otp($email, "User");
    }

    public function forgot_password($email, $otp, $password)
    {
        $email = strtolower($email);

        $query = $this->db->prepare('SELECT `otp` FROM users WHERE email = :email');

        $query->bindParam(':email', $email);

        $query->execute();

        $result = $query->fetch();

        if ($result->otp == $otp) {
            $this->change_password($email, $password);
            return true;
        } else {
            return false;
        }
    }
}
