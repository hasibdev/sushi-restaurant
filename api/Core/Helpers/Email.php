<?php

namespace Jara\Core\Helpers;

use Jara\App\Configs\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    public static function send($to_email, $to_name, $subject, $body, $alt_body)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = Config::$smtp_host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = Config::$smpt_username;                     //SMTP username
            $mail->Password   = Config::$smpt_password;                       //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = Config::$smpt_port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(Config::$smpt_from_email, Config::$smpt_from_name);
            $mail->addAddress($to_email, $to_name);
            $mail->addReplyTo(Config::$smpt_reply_to, Config::$app_name);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $alt_body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
