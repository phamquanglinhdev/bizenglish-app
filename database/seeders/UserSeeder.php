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
            'type' => 0,
        ]);

        for ($i = 1; $i < 30; $i++) {
            $type = rand(0,3);
            $data = [
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'password' => Hash::make("password"),
                'type' => $type,
            ];
            if($type == 3){
                $data["student_type"]=rand(0,2);
            }
            User::create($data);
        }
    }
}
