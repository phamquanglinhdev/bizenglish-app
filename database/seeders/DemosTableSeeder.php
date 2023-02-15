<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('demos')->delete();
        
        \DB::table('demos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'grade' => 'DEMO LINH',
                'students' => 'Phạm Quang Linh',
                'teacher_id' => 102,
                'client_id' => 5,
                'date' => '2023-02-15',
                'start' => '15:51',
                'end' => '16:51',
                'duration' => 60,
                'lesson' => '01 : Greeting',
                'information' => '<p>AAA</p>',
                'hour_salary' => 180000,
                'log_salary' => 180000,
                'teacher_video' => '{"provider":"youtube","id":"Zl5y5wTEYao","title":"Lý giải làn sóng sa thải nhân sự công nghệ | VTV24","image":"https://i.ytimg.com/vi/Zl5y5wTEYao/maxresdefault.jpg","url":"https://www.youtube.com/watch?v=Zl5y5wTEYao"}',
                'disable' => 0,
                'status' => '[{"name":"0","time":"","message":""}]',
                'question' => NULL,
                'assessment' => 'Animal',
                'attachments' => '[null]',
                'drive' => NULL,
                'created_at' => '2023-02-15 06:52:32',
                'updated_at' => '2023-02-15 06:52:32',
            ),
            1 => 
            array (
                'id' => 2,
                'grade' => 'SUPE',
                'students' => 'Phạm Minh',
                'teacher_id' => 664,
                'client_id' => 175,
                'date' => '2023-02-15',
                'start' => '15:51',
                'end' => '16:51',
                'duration' => 60,
                'lesson' => '01 : Greeting',
                'information' => '<p>AAA</p>',
                'hour_salary' => 180000,
                'log_salary' => 180000,
                'teacher_video' => '{"provider":"youtube","id":"Zl5y5wTEYao","title":"Lý giải làn sóng sa thải nhân sự công nghệ | VTV24","image":"https://i.ytimg.com/vi/Zl5y5wTEYao/maxresdefault.jpg","url":"https://www.youtube.com/watch?v=Zl5y5wTEYao"}',
                'disable' => 0,
                'status' => '[{"name":"0","time":"","message":""}]',
                'question' => NULL,
                'assessment' => 'Animal',
                'attachments' => '[null]',
                'drive' => NULL,
                'created_at' => '2023-02-15 06:52:32',
                'updated_at' => '2023-02-15 06:52:32',
            ),
        ));
        
        
    }
}