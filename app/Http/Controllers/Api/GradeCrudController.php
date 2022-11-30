<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Grade;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeCrudController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $grade = Grade::limit(30)->where("disable", 0)->get();
        return view("api.grade", ["grades" => $grade]);
    }

    public function filter(Request $request)
    {
        $grade_id = [];
        $first = true;
        if (isset($request->staff_filter)) {
            $staff_id = [];
            $value = $request->staff_filter;
            $staffs = Staff::where("name", "like", "%$value%")->where("disable", 0)->where("type", 0)->get();
            foreach ($staffs as $staff) {
                $grades = $staff->Grades()->get();
                foreach ($grades as $grade) {
                    $staff_id[] = $grade->id;
                }
            }
            $first = false;
            $grade_id = $staff_id;
        }
        if (isset($request->student_filter)) {
            $student_id = [];
            $value = $request->student_filter;
            $staffs = Student::where("name", "like", "%$value%")->where("disable", 0)->where("type", 3)->get();
            foreach ($staffs as $staff) {
                $grades = $staff->Grades()->get();
                foreach ($grades as $grade) {
                    $student_id[] = $grade->id;
                }
            }
            if ($first) {
                $first = false;
                $grade_id = $student_id;
            } else {
                $grade_id = array_intersect($grade_id, $student_id);
            }
        }
        if (isset($request->teacher_filter)) {
            $teacher_id = [];
            $value = $request->teacher_filter;
            $staffs = Teacher::where("name", "like", "%$value%")->where("disable", 0)->where("type", 1)->get();
            foreach ($staffs as $staff) {
                $grades = $staff->Grades()->get();
                foreach ($grades as $grade) {
                    $teacher_id[] = $grade->id;
                }
            }
            if ($first) {
                $first = false;
                $grade_id = $teacher_id;
            } else {
                $grade_id = array_intersect($grade_id, $teacher_id);
            }
        }
        if (isset($request->client_filter)) {
            $client_id = [];
            $value = $request->client_filter;
            $staffs = Client::where("name", "like", "%$value%")->where("disable", 0)->where("type", 2)->get();
            foreach ($staffs as $staff) {
                $grades = $staff->Grades()->get();
                foreach ($grades as $grade) {
                    $client_id[] = $grade->id;
                }
            }
            if ($first) {
                $first = false;
                $grade_id = $client_id;
            } else {
                $grade_id = array_intersect($grade_id, $client_id);
            }
        }
        if (isset($request->status)) {
            $status_id = [];
            foreach ($request->status as $status) {
                $grades = Grade::where("disable", 0)->where("status", $status)->get();
                foreach ($grades as $grade) {
                    $status_id[] = $grade->id;
                }
            }
            if ($first) {
                $first = false;
                $grade_id = $status_id;
            } else {
                $grade_id = array_intersect($grade_id, $status_id);
            }
        }
//        dd($grade_id);
        if ($first) {
            $grades = Grade::where("disable", 0)->get();

        } else {
            $grades = Grade::whereIn('id', $grade_id)->where("disable", 0)->get();
        }
        return view("api.grade", ["grades" => $grades]);
    }
}
