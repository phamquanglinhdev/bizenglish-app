<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function getUserData()
    {
        $teacher = Teacher::where("disable", 0)->where("type", 1)->get(["id", "name"]);
        $student = Student::where("disable", 0)->where("type", 3)->get(["id", "name"]);
        $client = Teacher::where("disable", 0)->where("type", 2)->get(["id", "name"]);
        return response()->json([
            'teacher' => $teacher,
            'student' => $student,
            'client' => $client,
        ], 200);
    }

    public function storeNewGrade(Request $request)
    {
        $grade = [
            'name' => $request->name,
            'pricing' => $request->pricing,
            'information' => $request->information,
            'attachment' => $request->attachment,
            'status' => $request->status,
            'minutes' => $request->minutes,
            'zoom' => $request->zoom,
            'time' => $request->time,
        ];
        try {
            $grade = Grade::create($grade);
            foreach ($request->teachers as $teacher) {
                DB::table("teacher_grade")->insert([
                    'teacher_id' => $teacher,
                    'grade_id' => $grade->id,
                ]);
            }
            foreach ($request->students as $student) {
                DB::table("student_grade")->insert([
                    'student_id' => $student,
                    'grade_id' => $grade->id,
                ]);
            }
            foreach ($request->clients as $client) {
                DB::table("client_grade")->insert([
                    'client_id' => $client,
                    'grade_id' => $grade->id,
                ]);
            }
            DB::table("staff_grade")->insert([
                'staff_id' => $request->staffs,
                'grade_id' => $grade->id,
            ]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
//            return response()->json(["message" => $exception->getMessage()], 500);
        }

        return $grade;
    }

    public function getGrades(Request $request)
    {
        $data = [];
        $grades = Grade::where("disable", 0)->get();
        foreach ($grades as $grade) {
            $item = new \stdClass();
            $item->id = $grade->id;
            $item->name = $grade->name;
            $item->students = $grade->Student()->get(["id", "name"]);
            $item->teachers = $grade->Teacher()->get(["id", "name"]);
            $item->staffs = $grade->Staff()->get(["id", "name"]);
            $item->clients = $grade->Client()->get(["id", "name"]);
            $item->zoom = $grade->zoom ?? "https://fb.me/linhcuenini";
            $item->pricing = number_format($grade->pricing);
            $item->minutes = $grade->minutes;
            $item->remaining = $grade->getRs();
            $item->attachment = $grade->attachment;
            switch ($grade->status) {
                case 0:
                    $item->status = "Đang học";
                    break;
                case 1:
                    $item->status = "Đã kết thúc";
                    break;
                case 2:
                    $item->status = "Đang bảo lưu";
                    break;
            }
            $item->created_at = $grade->created_at;
            $data[] = $item;
        }
        return $data;
    }

    public function getGrade($id)
    {
        $grade = Grade::where("id", $id)->where("disable", 0)->first();
        if (Grade::where("id", $id)->where("disable", 0)->count() != 0) {
            $teachers = [];
            foreach ($grade->Teacher()->get() as $item) {
                $teachers[] = $item->id;
            }
            $grade->teachers = $teachers;
            $students = [];
            foreach ($grade->Student()->get() as $item) {
                $students[] = $item->id;
            }
            $grade->students = $students;
            $clients = [];
            foreach ($grade->Client()->get() as $item) {
                $clients[] = $item->id;
            }
            $grade->clients = $clients;
            return $grade;
        } else {
            return response()->json(null, 500);
        }
    }

    public function updateGrade(Request $request)
    {
        $grade = [
            'name' => $request->name,
            'pricing' => $request->pricing,
            'information' => $request->information,
            'attachment' => $request->attachment,
            'status' => $request->status,
            'minutes' => $request->minutes,
            'zoom' => $request->zoom,
            'time' => $request->time,
        ];
        try {
            $grade = Grade::find($request->id)->update($grade);
            DB::table("teacher_grade")->where("grade_id", $request->id)->delete();
            DB::table("student_grade")->where("grade_id", $request->id)->delete();
            DB::table("client_grade")->where("grade_id", $request->id)->delete();
            DB::table("staff_grade")->where("grade_id", $request->id)->delete();
            foreach ($request->teachers as $teacher) {
                DB::table("teacher_grade")->insert([
                    'teacher_id' => $teacher,
                    'grade_id' => $request->id,
                ]);
            }

            foreach ($request->students as $student) {
                DB::table("student_grade")->insert([
                    'student_id' => $student,
                    'grade_id' => $request->id,
                ]);
            }
            foreach ($request->clients as $client) {
                DB::table("client_grade")->insert([
                    'client_id' => $client,
                    'grade_id' => $request->id,
                ]);
            }
            DB::table("staff_grade")->insert([
                'staff_id' => $request->staffs,
                'grade_id' => $request->id,
            ]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
//            return response()->json(["message" => $exception->getMessage()], 500);
        }

        return $grade;
    }

    public function deleteGrade($id)
    {
        try {
            Grade::find($id)->delete();
            return response()->json(["message" => "OK"], 200);
        } catch (\Exception $exception) {
            return response()->json($exception, 500);
        }
    }
}
