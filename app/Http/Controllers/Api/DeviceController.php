<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $user = $request->user();
        $token = $request->token ?? null;
        $platform = $request->platform ?? "android";
        if ($token == null) {
            return response()->json(null, 400);
        }
        $data = [
            'user_id' => $user->id,
            'token' => $token,
            'platform' => $platform,
        ];
        Device::create($data);
        return response()->json(["message" => "Success"], 200);
    }
}
