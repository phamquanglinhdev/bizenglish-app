<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Time;
use Illuminate\Database\Seeder;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = Teacher::where("type", "=", 1)->get();
        foreach ($teachers as $teacher) {
            Time::create([
                "teacher_id" => $teacher->id
            ]);
        }
    }
}
