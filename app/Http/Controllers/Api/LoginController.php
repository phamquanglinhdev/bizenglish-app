<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (!User::where("email", $request->email)->count() == 1) {
            return response()->json(null, 400);
        }
        $user = User::where("email", $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(null, 400);
        }
        $token = $user->createToken("Bearer");
        return response()->json(
            [
                'token' => 'Bearer ' . $token->plainTextToken
            ]
            , 200);
    }
}
