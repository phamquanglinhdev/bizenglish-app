<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class OTPController extends Controller
{
    public function sendOTP(Request $request)
    {
        $phone = $request->phone ?? "0904800240";
        $message = $request->message ?? "Lỗi ! từ $phone";
        Otp::create([
            'phone' => $phone,
            'message' => $message,
        ]);
        return response()->json(["message", "Thành công"], 200);
    }

    public function showByPhone(Request $request)
    {
        $phone = $request->phone ?? "0904800240";
        return Otp::where("phone", $phone)->orderBy("created_at", "DESC")->get() ?? null;
    }

    public function listPhone(Request $request): Collection
    {
        return Otp::select("phone")->distinct()->get();
    }
}
