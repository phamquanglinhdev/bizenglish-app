<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class ContestController extends Controller
{
    public function playRequest($contest_id): JsonResponse|Redirector|RedirectResponse|Application
    {
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
        $user = Customer::query()->where("id", backpack_user()->id)->first();
        $token = "Bearer " . $user->createToken("Bearer")->plainTextToken;
        return redirect("http://localhost:3000?token=" . $token . "&contest=" . $contest_id . "&user_id=" . backpack_user()->id);
    }

    public function checkContest(Request $request): bool
    {
        /**
         * @var Contest $contest
         */
        $contest = Contest::query()->find($request["contest_id"]);
        $total = count($contest["body"]);
        $correctAnswer = 0;
        foreach ($request["submitData"] as $data) {
            if (isset($data["user_choose"])) {
                if ($data["user_choose"] == $data["correct"]) {
                    $correctAnswer += 1;
                }
            }
            if (isset($data["user_type"])) {
                if ($data["user_type"] == $data["text_correct"]) {
                    $correctAnswer += 1;
                }
            }
        }
        DB::table("customer_contest")->where("contest_id", $request["contest_id"])->where("customer_id", $request["user_id"])->update([
            'correct' => $correctAnswer,
            'total' => $total,
            'correct_task' => $request['submitData'],
            'score' => ($correctAnswer / $total) * 100
        ]);
        if ($contest["next_contest"] != null) {
            if ($contest["min_point"] <= (($correctAnswer / $total) * 100)) {
                DB::table("customer_contest")->insert([
                    "contest_id" => $contest["next_contest"],
                    "customer_id" => $request["user_id"]
                ]);
            }
        }
        return true;
    }

    public function getContest(Request $request)
    {
        /**
         * @var  Contest $contest
         */
        $contest_id = $request["contest_id"];
        $id = $request["user_id"];
        $contest = Contest::query()->whereHas("customers", function (Builder $customer) use ($id) {
            $customer->where("users.id", $id);
        })->where("id", $contest_id)->first();
        if (!$contest) {
            return response()->json(['message' => 'Không tìm thấy bài Test'], 404);
        }
        $pivot = $contest->Customers()->wherePivot("customer_id", $id)->first();
        if ($pivot["pivot"]['score'] != null) {
            return response()->json(['message' => 'Bạn đã làm bài Test này '], 401);
        }
        return [
            'name' => $pivot["name"],
            'title' => $contest['title'],
            'limit' => $contest['limit_time'],
            'body' => $contest['body'],
        ];

    }

}
