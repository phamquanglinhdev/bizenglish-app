<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StaffApiController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $start = ($page - 1) * 10 + 1;
        $staff = Staff::where("disable", 0)->where("type", 0)->orderBy("code", "ASC")->skip($start)->take(10)->get(["id", "code", "name", "job", "phone", "email"]);
        return response()->json($staff);
    }

    public function student(Request $request)
    {
        return Student::where("disable", 0)->where("type", 3)->get(["id", "name"]);
    }

    public function show(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        $data = [
            'code' => $request->code ?? null,
            'avatar' => $request->avatar ?? null,
            'job' => $request->job ?? null,
            'phone' => $request->phone ?? null,
            'email' => $request->email ?? null,
            'extra' => $request->extras ?? null,
            'address' => $request->address ?? null,
            'password' => Hash::make($request->password),
            'type' => 0,
            'name' => $request->name ?? null,
        ];
        try {
//            return var_dump($data["extras"]);
            $staff = Staff::create($data);
            foreach ($request->students as $student) {
                Student::find($student["id"])->update(["staff_id" => $staff->id]);
            }
            return response()->json(["message" => "Thành công"], 200);
        } catch (\Exception $exception) {
            Log::alert($exception);
            return response()->json(["message" => "Thành công"], 400);
        }
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }
}
