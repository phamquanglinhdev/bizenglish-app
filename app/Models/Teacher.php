<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ["id"];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function Detail()
    {
        return view("components.detail", ['route' => route("admin.teacher.detail", $this->id)]);
    }

    public function Grades()
    {
        return $this->belongsToMany(Grade::class, "teacher_grade", "teacher_id", "grade_id");
    }

    public function Logs()
    {
        return $this->hasMany(Log::class, "teacher_id", "id");
    }

    public function Skills()
    {
        return $this->belongsToMany(Skill::class, "teacher_skill", "teacher_id", "skill_id");
    }

    public function Time()
    {
        return $this->hasOne(Time::class, "teacher_id", "id");
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function getID()
    {
        return $this->id ?? Teacher::max("id") + 1;
    }

    public function setPasswordAttribute($value)
    {
        if ($value != "") {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function setPrivate()
    {
        $this->attributes['private_key'] = Str::random(20);
    }
//    public function setCodeAttribute() {
//        $this->attributes['code'] = "GV".$this->getID();
//
//    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function genesis(): bool
    {

        if (Time::where("teacher_id", "=", $this->id)->count() == 0) {
            return true;
        }
        return false;
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'extra' => 'json',
    ];

}
