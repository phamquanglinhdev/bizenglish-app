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

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function Grade()
    {
        return $this->belongsTo(Grade::class, "grade_id", "id");
    }

    public function detail()
    {
        return view("components.detail", ['route' => route("admin.log.detail", $this->id)]);
    }

    public function pushExercise()
    {
        return view("components.push", ['route' => route('exercise.create', ['log_id' => $this->id])]);
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

    public function setTeacherVideoAttribute($value)
    {
        $attribute_name = "teacher_video";
        $disk = "uploads_video";
        $destination_path = "";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

//         return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function reported($id)
    {
        $isExist = $this->belongsToMany(Student::class,"student_log","log_id","student_id")->count();
        return $isExist > 0;
    }
    public function Students(){
        return $this->belongsToMany(Student::class,"student_log","log_id","student_id");
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
