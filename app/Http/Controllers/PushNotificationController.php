<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PushNotificationController extends Controller
{
    public function index()
    {
        $body = json_encode([
            "to" => "ExponentPushToken[KC0ZyDPvakuukHkKOCKfK4]",
            "title" => "Thông báo mới",
            "body" => "Đến giờ ngủ rồi",
            "channelId" => 'default',
        ]);
        $response = Http::withBody($body, 'application/json')->post('https://exp.host/--/api/v2/push/send');
    }
}
