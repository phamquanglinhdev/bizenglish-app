<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Grade extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'grades';
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
    public function getStatus()
    {
        $status = ["Đang học", "Đã kết thúc", "Đang bảo lưu"];
        return $status[$this->attributes["status"]];
    }

    public function fewDate(): bool
    {
        $minutes = $this->Logs()->count("duration");
       return ($this->minutes)-$minutes > 60;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function Student()
    {
        return $this->belongsToMany(User::class, "student_grade", "grade_id", "student_id");
    }

    public function Teacher()
    {
        return $this->belongsToMany(User::class, "teacher_grade", "grade_id", "teacher_id");
    }

    public function Client()
    {
        return $this->belongsToMany(User::class, "client_grade", "grade_id", "client_id");
    }
    public function Staff()
    {
        return $this->belongsToMany(User::class, "staff_grade", "grade_id", "staff_id");
    }

    public function Logs()
    {
        return $this->hasMany(Log::class, "grade_id", "id");
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOwner($query)
    {
        $grades = DB::table("staff_grade")->where("staff_id",backpack_user()->id)->get();
        if($grades->count()>0){
            $query->where("id",$grades->first()->grade_id);
            foreach ($grades as $grade){
                $query->orWhere("id",$grade->grade_id);
            }
        }else{
            $query->where("id",-1);
        }
        return $query;


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
