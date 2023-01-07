<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function userAccount()
    {
        clearstatcache();
        if (backpack_user()->type == -1)
            return redirect("admin/edit-account-info");
        if (backpack_user()->type == 0)
            return view("staff-detail", ['data' => \App\Models\Staff::find(backpack_user()->id)]);
        if (backpack_user()->type == 1)
            return redirect("admin/teaching?teacher_id=" . backpack_user()->id);
        if (backpack_user()->type == 2)
            return redirect("admin/teaching?client_id=" . backpack_user()->id);
        if (backpack_user()->type == 3)
            return view("student-detail", ['data' => \App\Models\Student::find(backpack_user()->id)]);
    }

    public function maintaining()
    {
        return view("relax");
    }

    public function maintainList()
    {
        $maintain = DB::table("settings")->where("name", "maintain")->first();
        $maintainIP = DB::table("settings")->where("name", "maintain_ip")->first();
        return view("manager.setting-tool", ["maintain" => $maintain->value, "maintainIP" => $maintainIP->value]);
    }

    public function switchMaintain()
    {
        $maintain = DB::table("settings")->where("name", "maintain")->first();
        if ($maintain->value == "off") {
            DB::table("settings")->where("name", "maintain")->update([
                "value" => "on",
            ]);
            DB::table("settings")->where("name", "maintain_ip")->update([
                "value" => $_SERVER["REMOTE_ADDR"]
            ]);
        } else {
            DB::table("settings")->where("name", "maintain")->update([
                "value" => "off",
            ]);
        }
        return redirect()->back();
    }
}
