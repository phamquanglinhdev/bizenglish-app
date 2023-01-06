<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Time;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $maintain = DB::table("settings")->where("name", "maintain")->first();
        if ($maintain != null && $maintain->value == "on") {
            $remote_ip = DB::table("settings")->where("name", "maintain_ip")->first();
            if ($remote_ip->value != $_SERVER["REMOTE_ADDR"]) {
                dd("Sever đang bảo trì, vui lòng quay lại sau");
            }
        }
        if (isset($_COOKIE["language"])) {
            app()->setLocale("en");
        } else {
            app()->setLocale("vn");
        }
        try {
            $teachers = Teacher::where("type", "=", 1)->get();
            foreach ($teachers as $teacher) {
                if ($teacher->genesis()) {
                    $matrix = [];
                    for ($i = 0; $i < 3; $i++) {
                        for ($j = 0; $j < 7; $j++) {
                            $matrix[$i][$j] = "-";
                        }
                    }
                    $morning = Time::ArrToString($matrix);
                    $evening = Time::ArrToString($matrix);
                    $afternoon = Time::ArrToString($matrix);

                    Time::create([
                        "teacher_id" => $teacher->id,
                        "morning" => $morning,
                        "afternoon" => $afternoon,
                        "evening" => $evening,
                    ]);
                }
            }
        } catch (\Exception $exception) {

        }

//        try {
//            $pivots = DB::table("student_grade")->get();
//            foreach ($pivots as $pivot) {
//                if (Student::find($pivot->student_id) == null) {
//                    DB::table("student_grade")->where("student_id", "=", $pivot->student_id)->delete();
//                }
//            }
//        } catch (\Exception $exception) {
//
//        }
        Builder::defaultStringLength(191);
    }
}
