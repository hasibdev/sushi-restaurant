<?php

/**
 * JaraCMS
 * 
 * @package JaraCMS
 * @version 1.0.0
 * @api 1.0.0
 * @author Obayed Opu <obayed.opu@gmail.com>
 * @copyright 2021 Obayed Opu
 * @license GPL2
 * @source https://github.com/opuu/flatapi based on flatapi by Obayed Opu
 */

use Jara\App\Configs\Config;

/**
 *  Define the root directory as BASEPATH
 * @since 1.0.0
 */
define('BASEPATH', __DIR__);

/**
 * Set default timezone
 */

date_default_timezone_set('Europe/Paris');


/**
 * Require the autoloader file.
 */
require __DIR__ . '/Vendor/autoload.php';

// error reporting
if (Config::$app_debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ERROR | E_PARSE);
}


/**
 * Cross Domain Access
 */


header('Content-Type: application/json; charset=utf-8');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], Config::$allowed_domains)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: Origin, Authorization, X-User, X-Requested-With, Content-Type, Accept");
    exit(0);
}

/**
 * Require the routes and initialize the app
 */
require __DIR__ . '/Routes/Routes.php';
