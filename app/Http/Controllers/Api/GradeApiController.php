<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class GradeApiController extends Controller
{
    public function index(Request $request)
    {
        $grades = Grade::where("disable", 0)->get();
        $data = [];
        foreach ($grades as $grade) {
            $item = new \stdClass();
            $item->id = $grade->id;
            $item->name = $grade->name;
            $item->students = $grade->Student()->where("disable", 0)->get(["name", "id"]);
            $item->teachers = $grade->Teacher()->where("disable", 0)->get(["name", "id"]);
            $item->staffs = $grade->Staff()->where("disable", 0)->get(["name", "id"]);
            $item->clients = $grade->Client()->where("disable", 0)->get(["name", "id"]);
            $item->zoom = $grade->zoom;
            $item->pricing = number_format($grade->pricing);
            $item->minutes = $grade->minutes;
            $item->remaining = $grade->getRs();
            $item->attachment = $grade->attachment;
            $item->status = $grade->getStatus();
            $item->created = Carbon::make($grade->created_at)->format("d-m-Y H:m:s");
            $data[] = $item;
        }
        return $data;
//        return \response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function people(Request $request)
    {
        $students = Student::where("type", 3)->where("disable", 0)->get(["id", "name"]);
        $teachers = Teacher::where("type", 1)->where("disable", 0)->get(["id", "name"]);
        $clients = Client::where("type", 2)->where("disable", 0)->get(["id", "name"]);
        $data = new \stdClass();
        $data->students = $students;
        $data->teachers = $teachers;
        $data->clients = $clients;
        return \response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = [
            'name' => $request->name ?? function () {
                    return $this->loss("tên lớp");
                },
            'pricing' => $request->pricing ?? function () {
                    return $this->loss("gói học phí");
                },
            'information' => $request->information ?? null,
            'attachment' => $request->attachment ?? null,
            'status' => $request->status ?? 0,
            'disable' => 0,
            'minutes' => $request->minutes ?? null,
            'time' => $request->time ?? null,
            'zoom' => $request->zoom ?? null
        ];
        $grade = Grade::create($data);
        $teachers = $request->teachers ?? null;
        foreach ($teachers as $teacher) {
            DB::table("teacher_grade")->insert([
                'teacher_id' => $teacher,
                'grade_id' => $grade->id,
            ]);
        }
        $clients = $request->clients ?? null;
        foreach ($clients as $client) {
            DB::table("client_grade")->insert([
                'client_id' => $client,
                'grade_id' => $grade->id,
            ]);
        }
        $students = $request->students ?? null;
        foreach ($students as $student) {
            DB::table("student_grade")->insert([
                'student_id' => $student,
                'grade_id' => $grade->id,
            ]);
        }
        DB::table("staff_grade")->insert([
            'staff_id' => $user->id,
            'grade_id' => $grade->id,
        ]);
//        return $grade;
        return \response()->json(["message" => "Thành công"], 200);
    }

    public function loss($value)
    {
        return \response()->json(["message" => "Thiếu " . $value]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request): Response
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
