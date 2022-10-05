<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $query = DB::table("grades");
        $query = $query->rightJoin("staff_grade", "grades.id", "=", "staff_grade.grade_id")
            ->joinSub("users", "staffes", "users.id", "=", "staff_grade.staff_id");
        return $query->where("staffes.name", "like", "%Minh%")->get();
    }
}
