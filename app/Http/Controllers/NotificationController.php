<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $users = User::where("disable", 0)->get(["name", "id", "code"]);
        return view("manager.send-notification", ["users" => $users]);
    }

    public function send(Request $request)
    {
        $title = $request->title ?? "";
        $description = $request->description ?? "";
        $target = [];
        $people = $request->people ?? [];
        if (in_array("everyone", $people)) {
            $all = User::where("disable", 0)->get(["id"]);
            foreach ($all as $item) {
                $target[] = $item->id;
            }
        }
        if (in_array("all-student", $people)) {
            $student = [];
            $students = Student::where("disable", 0)->where("type", 3)->get(["id"]);
            foreach ($students as $item) {
                $student[] = $item->id;
            }
            if ($target != []) {
                $target = array_merge($target, $student);
            } else {
                $target = $student;
            }
        }
        if (in_array("all-teacher", $people)) {
            $teacher = [];
            $teachers = Teacher::where("disable", 0)->where("type", 1)->get(["id"]);
            foreach ($teachers as $item) {
                $teacher[] = $item->id;
            }
            if ($target != []) {
                $target = array_merge($target, $teacher);
            } else {
                $target = $teacher;
            }
        }
        if (in_array("all-client", $people)) {
            $client = [];
            $clients = Client::where("disable", 0)->where("type", 2)->get(["id"]);
            foreach ($clients as $item) {
                $client[] = $item->id;
            }
            if ($target != []) {
                $target = array_merge($target, $client);
            } else {
                $target = $client;
            }
        }
        if (in_array("all-staff", $people)) {
            $staff = [];
            $staffs = Staff::where("disable", 0)->where("type", 0)->get(["id"]);
            foreach ($staffs as $item) {
                $staff[] = $item->id;
            }
            if ($target != []) {
                $target = array_merge($target, $staff);
            } else {
                $target = $staff;
            }
        }
        foreach ($people as $person) {
            if ((int)$person) {
                $target = array_merge($target, [(int)$person]);
            }
        }
        $data = new \stdClass();
        $data->type = "link";
        $data->link = $request->link??null;
        foreach ($target as $value) {
            $user = User::find($value);
            $devices = $user->Devices()->get();
            foreach ($devices as $device) {
                echo "Token: $device->token <br>";
                PushNotificationController::ExpoPushNotification($device->token, $title, $description, $data);
            }
        }
    }
}
