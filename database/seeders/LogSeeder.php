<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 100; $i++) {
            $data = [
                'teacher_id'=>Teacher::where("type","=",1)->first()->id,
                'grade_id' => rand(1, 3),
                'start' => fake()->dateTime("now"),
                'end' => fake()->dateTime("now"),
                'duration' => rand(15, 45),
                'lesson' => 'Lesson Template: ' . fake()->name(),
                'information' => "Test",
                'hour_salary' => rand(120, 250) * 1000,
                'teacher_video' => '/upload/example'
            ];
            $log = Log::create($data);
            DB::table("logs")->where("id", "=", $log->id)->update(['teacher_video' => Hash::make("example") . ".mp4"]);
            $student = $log->Grade()->first()->Student()->get();
            foreach ($student as $item){
                DB::table("student_log")->insert([
                    'log_id'=>$log->id,
                    'student_id'=>$item->id,
                    'accept'=>1,
                    'comment'=>null,
                ]);
            }
        }
    }
}
