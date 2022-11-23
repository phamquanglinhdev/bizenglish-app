<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Time;
use Illuminate\Database\Schema\Builder;
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

        try {
            $freedomStudents = Student::where("staff_id", "=", null)->get();
            foreach ($freedomStudents as $student) {
                $grade = $student->Grade()->first();
                if (isset($grade->Staff()->first()->id)) {
                    $student->update(
                        ['staff_id' => $grade->Staff()->first()->id]
                    );
                } else {
                    $student->destroy();
                }
            }
        } catch (\Exception $exception) {

        }
        Builder::defaultStringLength(191);
    }
}
