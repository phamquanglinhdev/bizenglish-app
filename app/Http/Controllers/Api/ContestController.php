<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function playRequest($contest_id, $user_id)
    {
        /**
         * @var Contest $contest
         */
        $contest = Contest::query()->find($contest_id);
        $contest = Contest::query()->whereHas("customers", function (Builder $customer) {
            $customer->where("users.id", backpack_user()->id);
        })->where("id", $contest_id)->first();
        if (!$contest) {
            return response()->json(['message' => 'Không tìm thấy bài Test'], 404);
        }
        $pivot = $contest->Customers()->wherePivot("customer_id", backpack_user()->id)->first();
        if ($pivot["pivot"]['score'] != null) {
            return response()->json(['message' => 'Bạn đã làm bài Test này '], 401);
        }
        $user = Customer::query()->where("id", $user_id)->first();
        $token = "Bearer " . $user->createToken("Bearer")->plainTextToken;
        return redirect("http://localhost:3000?token=" . $token . "&contest=" . $contest_id);
    }
}
