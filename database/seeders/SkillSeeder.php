<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//-
//-
//-
//-
//-
//-
//-	.

    public function run()
    {
        $name = [
            "GV dạy giao tiếp cơ bản",
            "GV dạy giao tiếp nâng cao",
            "GV IELTS",
            "GV TOEIC",
            "GV tiếng Anh trẻ em 3 – 10 tuổi",
            "GV dạy Business English",
            "GV luyện thi cấp 2, cấp 3",
        ];
        foreach ($name as $item) {
            Skill::create(
                [
                    'name' => $item
                ]
            );
        }
    }
}
