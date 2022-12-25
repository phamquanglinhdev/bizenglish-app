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

    public function edit(Request $request)
    {
        $id = $request->id ?? null;
        if ($id == null) {
            return response()->json(null, 404);
        }
        $staff = Staff::where("id", $id)->first();
        $data = new \stdClass();
        $data->code = $staff->code;
        $data->avatar = $staff->avatar;
        $data->name = $staff->name;
        $data->job = $staff->job;
        $data->email = $staff->email;
        $data->phone = $staff->phone;
        $data->extras = $staff->extras;
        $data->facebook = $staff->facebook;
        $data->students = $staff->Students()->get(["id", "name"]);
        $data->address = $staff->address;
        return response()->json($data, 200);
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
            'facebook' => $request->facebook ?? null,
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
        $data = [
            'code' => $request->code ?? null,
            'avatar' => $request->avatar ?? null,
            'job' => $request->job ?? null,
            'phone' => $request->phone ?? null,
            'email' => $request->email ?? null,
            'extra' => $request->extras ?? null,
            'address' => $request->address ?? null,
            'type' => 0,
            'name' => $request->name ?? null,
            'facebook' => $request->facebook ?? null,
        ];
        try {
            $staff = Staff::where("id", $request->id)->update($data);
            Student::where("staff_id", $request->id)->update(["staff_id" => null]);
            foreach ($request->students as $student) {
                return var_dump($student["id"]);
                Student::find($student->id)->update(["staff_id" => $staff->id]);
            }
            return response()->json(["message" => "Thành công"], 200);
        } catch (\Exception $exception) {
            Log::alert($exception);
            return response()->json(["message" => "Thành công"], 400);
        }
    }

    public function destroy(Request $request)
    {
        //
    }
}
