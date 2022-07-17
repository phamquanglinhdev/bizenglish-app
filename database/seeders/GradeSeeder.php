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
            $data = [
                'name'=>"C00".$i,
                'pricing'=>rand(10,30)*10000,
                'status'=>rand(0,2),
                'minutes'=>rand(4,10)*100,
                'attachment'=>'/uploads/document/example.docx',
            ];
            $grade = Grade::create($data);
            $student = User::where("type","=",3)->first();
            DB::table("student_grade")->insert([
                'student_id'=>$student->id,
                'grade_id'=>$grade->id,
            ]);
            $teacher = User::where("type","=",1)->first();
            DB::table("teacher_grade")->insert([
                'teacher_id'=>$teacher->id,
                'grade_id'=>$grade->id,
            ]);
            $client = User::where("type","=",2)->first();
            DB::table("client_grade")->insert([
                'client_id'=>$client->id,
                'grade_id'=>$grade->id,
            ]);
            $staff = User::where("type","=",0)->first();
            DB::table("staff_grade")->insert([
                'staff_id'=>$staff->id,
                'grade_id'=>$grade->id,
            ]);
        }
    }
}
