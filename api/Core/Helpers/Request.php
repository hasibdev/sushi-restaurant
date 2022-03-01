<?php

namespace Jara\Core\Helpers;

use Jara\App\Configs\Config;

class Request
{
    public static function require()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $args = func_get_args();
        $params = [];
        $requests = [];

        foreach ($args as $param) {
            $params[$param] = true;
        }

        $request = json_decode(file_get_contents('php://input'), true);


        foreach ($request as $name => $value) {
            $requests[$name] = true;
        }

        if ($requests == $params) {
            return $request;
        } else {
            $found = [];
            $not_found = [];
            foreach ($params as $key => $value) {
                if (isset($requests[$key]) && $requests[$key] === $value) {
                    $found[$key] = $value;
                } else {
                    $not_found[$key] = $value;
                }
            }

            if ($not_found === []) {
                return $request;
            } else {
                if (Config::$app_debug) {
                    $data = [
                        'message' => 'All required parameters are not provided.',
                        'required' => $params,
                        'provided' => $found,
                        'missing' => $not_found,
                    ];
                } else {
                    $data = null;
                }

                exit(Response::new(400, $data));
            }
        }
    }
}
