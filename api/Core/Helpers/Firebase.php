<?php

namespace Jara\Core\Helpers;

use Jara\App\Configs\Config;

class Firebase
{
    public static function send_notification($ids, $title, $body, $url)
    {
        $notification = [
            'title' => $title,
            'body' => $body,
            'click_action' => $url,
            'alert' => 'Order Received',
            'priority' => 'high',
            'sound' => Config::$app_url . '/uploads/notification.wav',
            'content_available' => true
        ];

        $data = [
            'title' => $title,
            'body' => $body,
            'click_action' => $url
        ];

        $headers = [
            'Authorization: key=' . Config::$firebase_key,
            'Content-Type: application/json'
        ];

        $fcmNotification = [
            'registration_ids' => $ids,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
            'sound' => Config::$app_url . '/uploads/notification.wav'
        ];

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $cRequest = curl_init();
        curl_setopt($cRequest, CURLOPT_URL, $fcmUrl);
        curl_setopt($cRequest, CURLOPT_POST, true);
        curl_setopt($cRequest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cRequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cRequest, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($cRequest);
        curl_close($cRequest);
        return $result;
    }
}
