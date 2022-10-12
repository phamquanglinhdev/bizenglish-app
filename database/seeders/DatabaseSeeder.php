<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SkillSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(LogSeeder::class);
        $this->call(TimeSeeder::class);
    }
}
