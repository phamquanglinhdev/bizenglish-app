<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "BizEnglish Admin",
            'email' => "admin@biz.com",
            'password' => Hash::make("password"),
            'type' => -1,
            'code'=>"AD1",
        ]);
        User::create([
            'name' => "Trần Minh Hạ",
            'email' => "tranminhha@biz.com",
            'password' => Hash::make("password"),
            'code' => "NV1",
            "type"=>0,
        ]);

        for ($i = 1; $i < 30; $i++) {
            $type = rand(1,3);
            $data = [
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'password' => Hash::make("password"),
                'type' => $type,
            ];
            if($type == 1){
                $data["student_type"]=rand(0,2);
                $data["student_status"]=rand(0,2);
                $data["code"] = "GV".$i;

            }
            if($type == 3){
                $data["student_type"]=rand(0,2);
                $data["student_status"]=rand(0,2);
                $data["code"] = "HS".$i;
                $data["staff_id"] =2;

            }
            if($type == 2){
                $data["client_status"]=rand(0,2);
                $data["code"] = "DT".$i;

            }
            User::create($data);
        }
    }
}
