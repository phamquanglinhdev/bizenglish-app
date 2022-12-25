<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PushNotificationController extends Controller
{
    public static function ExpoPushNotification($to, $title, $body, $data)
    {
        $body = json_encode([
            "to" => $to,
            "title" => $title,
            "body" => $body,
            "channelId" => 'default',
            'data' => json_encode($data),
        ]);
        $response = Http::withBody($body, 'application/json')->post('https://exp.host/--/api/v2/push/send');
    }
}
