<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffApiController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $start = ($page - 1) * 10 + 1;
        $staff = Staff::where("disable", 0)->where("type", 0)->orderBy("code", "ASC")->skip($start)->take(10)->get(["id", "code", "name", "job", "phone", "email"]);
        return response()->json(['staffs' => $staff, 'students' => $this->studentList()]);
    }

    public function studentList()
    {
        $students = Student::where("disable", 0)->get(["id", "name"]);
        return $students;
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
            'extras' => json_encode($request->extras ?? null),
            'address' => $request->address ?? null,
            'password' => Hash::make($request->password ?? null),
            'type' => 0,
            'name' => $request->name ?? null,
        ];
        try {
            $staff = Staff::create($data);

        } catch (\Exception $exception) {
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
