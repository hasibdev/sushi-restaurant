<?php

namespace Jara\Core\Helpers;

class Response
{
    public static function new($code, $data = null)
    {
        $codes = [];
        $codes['200'] = 'Success.';
        $codes['400'] = 'Bad Request.';
        $codes['401'] = 'Unauthorized Request.';
        $codes['404'] = 'API Not Found.';
        $codes['500'] = 'Server Error.';
        $codes['502'] = 'Bad Gateway.';
        $codes['301'] = 'Moved Permanently.';
        $codes['302'] = 'Moved Temporarily.';

        $success = [200, 301, 302];

        http_response_code($code);

        $response = new \stdClass();

        $response->success = in_array($code, $success);
        $response->code = $code;
        $response->message = (!empty($codes[$code]) ? $codes[$code] : 'Unknown Error');

        if ($data) {
            $response->payload = $data;
        }

        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
