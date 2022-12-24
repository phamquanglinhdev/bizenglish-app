<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'logs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'status' => 'json',
        'attachments' => 'json',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function Exercises()
    {
        return $this->hasMany(Exercise::class, "log_id", "id");
    }

    public function Grade()
    {
        return $this->belongsTo(Grade::class, "grade_id", "id");
    }

    public function Client()
    {
        $rs = [];
        $grade = $this->Grade()->first();
        if (!isset($grade->name)) {
            return "-";
        }
        $client = $grade->Client()->get();
        foreach ($client as $data) {
            $rs[] = $data->name;
        }
        return implode(",", $rs);
    }

    public function detail()
    {
        return view("components.detail", ['route' => route("admin.log.detail", $this->id)]);
    }

    public function pushExercise()
    {
        return view("components.push", ['route' => route('exercise.create', ['log_id' => $this->id])]);
    }

    public function setAcceptLog()
    {
        $ac = $this->belongsToMany(Student::class, "student_log", "log_id", "student_id");
        if ($ac->count() == 1) {
            return "";
        }
        return view("components.detail", ['route' => route("admin.log.detail", $this->id), "title" => "Xác nhận thông tin"]);
    }

    public function setLogSalaryAttribute()
    {
        $this->attributes["log_salary"] = $this->duration / 60 * $this->hour_salary;
    }

    public function StatusShow()
    {
        if ($this->status != null) {
            $status = $this->status[0];
            $time = $status["time"];
            $name = $status["name"] * 1;
            switch ($name * 1) {
                case 0:
                    return "Học viên và giáo viên vào đúng giờ.";
                case 1:
                    return "Học viên vào muộn $time phút";
                case 2:
                    return "Giáo viên vào muộn $time phút";
                case 3:
                    return "Học viên hủy buổi học trước $time giờ";
                case 4:
                    return "Giáo viên hủy buổi học trước $time giờ";
                default:
                    return $status["message"];
            }
        }

    }

    public function StudentAccept()
    {
        $ac = DB::table("student_log")->where("log_id", "=", $this->id);
        if ($ac->count() == 0) {
            return "Chưa có HS xác nhận";
        } else {
            $students = DB::table("student_log")->where("log_id", "=", $this->id)->get();
            foreach ($students as $student) {
                $name = DB::table("users")->where("id", "=", $student->student_id)->first()->name;
                if ($student->accept == 0) {
                    $acp = "Đúng";
                } else {
                    $acp = "Sai";
                }
                $message = "<div> Xác nhận $acp </div>";
                if ($student->comment != null) {
                    $message .= "<div>Thông tin thêm: $student->comment</div> ";
                }


            }
            return $message;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function Comments()
    {
        return $this->hasMany(Comment::class, "log_id", "id");
    }

    public function Teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_id", "id");
    }

    public function getStudentList()
    {
        $result = [];
        $grade = $this->belongsTo(Grade::class, "grade_id", "id")->first();
        if (!isset($grade->name)) {
            return "-";
        }
        $students = $grade->Student()->get();
        foreach ($students as $item) {
            $result[] = $item->name;
        }
        return implode(",", $result);

    }

//    public function setTeacherVideoAttribute($value)
//    {
//        $attribute_name = "teacher_video";
//        $disk = "uploads_video";
//        $destination_path = "";
//
//        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
//
////         return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
//    }

    public function reported($id)
    {
        $isExist = $this->belongsToMany(Student::class, "student_log", "log_id", "student_id")->count();
        return $isExist > 0;
    }

    public function Students()
    {
        return $this->belongsToMany(Student::class, "student_log", "log_id", "student_id");
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeRep($query)
    {
        $grades = DB::table("student_grade")->where("student_id", "=", backpack_user()->id)->get();
        if ($grades->count() > 0) {
            $query->where("grade_id", $grades->first()->grade_id);
            foreach ($grades as $grade) {
                $query = $query->orWhere("grade_id", $grade->grade_id);
            }
        } else {
            $query->where("id", -1);
        }
        return $query;
//            ->join('student','grade.id' , '=' ,'logs.logs_id')
//            ->join('shop_user','shop_user.shop_id' , '=' ,'logs.id')
//            ->where('shop_user.user_id',backpack_user()->id)
//            ->select('logs.*');


    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getVideo()
    {
        return json_decode($this->teacher_video);
    }

    public function getHourSalary()
    {
        if ($this->teacher_id == backpack_user()->id || backpack_user()->type < 1)
            return "<div class='text-center'>" . number_format($this->hour_salary) . "</div>";
        return "<div class='text-center'>---</div>";

    }

    public function getLogSalary()
    {
        if ($this->teacher_id == backpack_user()->id || backpack_user()->type < 1)
            return "<div class='text-center'>" . number_format($this->log_salary) . "</div>";
        return "<div class='text-center'>---</div>";

    }

    public function setAttachmentsAttribute($value)
    {
        $attribute_name = "attachments";
        $disk = "uploads_document";
        $destination_path = "/";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
