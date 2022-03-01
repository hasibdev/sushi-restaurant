<?php

namespace Jara\App\Configs;

class Config
{
    public static $env = 'development'; // development, production

    // db config
    public static $db_host = 'localhost';
    public static $db_port = 3307;
    public static $db_name = 'restaurant-app';
    public static $db_username = 'hasib';
    public static $db_password = 'Abc@123456';

    // app config
    public static $app_name = 'SushiTown';
    public static $app_url = 'http://localhost:4000';
    public static $app_admin_url = 'http://localhost:8080';
    public static $app_theme_color = '#d0b44b';
    public static $app_debug = false;

    // firebase config
    public static $firebase_key = 'AAAAVNJWzvE:APA91bFmmFC2Gw2rTyPt05DnwZoz-CH1HjrORulNbq-NoveGzLMXkBo3pICzYjuvy9_CSiZdaZSt1CKf7oa_xnuBPM-Kt4Z7qgUT6i2wxoSNjkGJTom3cb14o2aB0Ud2swgLb7zxQbl_';

    // stripe config
    public static $stripe_key = 'sk_test_51GwGhEFAElPCKlx3qvajr6XkVPNuoK8NucsCqcTifO10V1FUxkk7SGmxJxCYmQXnaiigJIOv95I2QMuiUGrRJg5u00nQgSzyC8';

    // email config
    public static $smtp_host = 'smtp-relay.sendinblue.com';
    public static $smpt_port = 587;
    public static $smpt_username = 'obayed.opu@gmail.com';
    public static $smpt_password = 'LbJkEZmvDNB3jh7F';
    public static $smpt_from_email = 'no-reply@sushitown.fr';
    public static $smpt_from_name = 'SushiTown';
    public static $smpt_reply_to = 'no-reply@sushitown.fr';
    public static $admin_email = 'obayed.opu@gmail.com';

    // api access permission
    public static $allowed_domains = [
        'http://localhost:3000',
        'http://localhost:8080',
        // 'http://192.168.31.142:8080',
        // 'http://192.168.31.142:3000',
        // 'http://192.168.31.142:4000'
    ];

    public static $post_types = [
        'products',
        'additions',
        'orders',
        'pages'
    ];
}
