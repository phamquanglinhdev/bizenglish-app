<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffApiController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $start = ($page - 1) * 10 + 1;
        $staff = Staff::where("disable", 0)->where("type", 0)->orderBy("code", "ASC")->skip($start)->take(10)->get(["id", "code", "name", "job", "phone", "email"]);
        return response()->json($staff);
    }

    public function show(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        //
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
