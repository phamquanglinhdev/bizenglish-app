<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZegoCloudController extends Controller
{
    /**
     * @throws \Exception
     */
    public function signature(): string
    {
        $time = time();
        $appId = "136078041";
        $signatureNonce = rand(10000000,99999999);
        $serverSecret = "74eb720682e208249808cbb6ca79cdf4";
        $signature = md5($appId . $signatureNonce . $serverSecret . $time);
        return response()->json([
            'signature' => $signature,
            'appId' => $appId,
            'signatureNonce' => $signatureNonce,
            'timestamp' => $time
        ], 200);
    }
}
