<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=3;$i++){
            $name = fake()->company();
            $price =fake()->domainName();
            $data = [
                'name'=>$name,
                'pricing'=>$price,
                'status'=>rand(0,2),
            ];
            $grade = Grade::create($data);
            $students = User::where("type","=",3)->get();
            foreach ($students as $user){
                DB::table("student_grade")->insert([
                    'student_id'=>$user->id,
                    'grade_id'=>$grade->id,
                ]);
            }
            $teacher = User::where("type","=",1)->get();
            foreach ($teacher as $user){
                DB::table("teacher_grade")->insert([
                    'teacher_id'=>$user->id,
                    'grade_id'=>$grade->id,
                ]);
            }
            $client = User::where("type","=",2)->get();
            foreach ($client as $user){
                DB::table("client_grade")->insert([
                    'client_id'=>$user->id,
                    'grade_id'=>$grade->id,
                ]);
            }
        }
    }
}
