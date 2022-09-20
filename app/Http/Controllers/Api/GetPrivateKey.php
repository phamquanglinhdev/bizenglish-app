<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class GetPrivateKey extends Controller
{
    public function getKey(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!isset($request->email) || !isset($request->password)) {
            return Response::json([
                'message' => "Vui lòng điền đủ tài khoản mật khẩu"
            ], 404);
        }
        if (User::where("email", "=", $request->email)->count() == 0) {
            return Response::json([
                'message' => "Không tìm thấy tài khoản của bạn"
            ], 404);
        }
        $user = User::where("email", "=", $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return Response::json([
                'message' => "Sai mật khẩu"
            ], 404);
        }
        return Response::json([
            'key' => $user->private_key
        ], 200);
    }
}
