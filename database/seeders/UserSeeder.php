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
            'name'=>"BizEnglish Admin",
            'email'=>"admin@biz.com",
            'password'=>Hash::make("Linhz123@"),
            'type'=>0,
        ]);
    }
}
