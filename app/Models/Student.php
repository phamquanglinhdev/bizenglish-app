<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Student extends Model
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
//     protected $hidden = ['password'];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getID()
    {
        if (Student::where("type", 3)->orderBy("code", "DESC")->count() != 0) {
            $student = Student::where("type", 3)->orderBy("code", "DESC")->first();
            $code = str_replace("HV", "", $student->code);
            $code += 1;
            if ($code < 100) {
                $code = "HV0$code";
            } else {
                $code = "HV$code";
            }
            return $code;
        } else {
            return "HV001";
        }
    }

    public function setPasswordAttribute($value)
    {
        if ($value != "") {
            $this->attributes['password'] = Hash::make($value);
        }
    }
//     public function setCodeAttribute() {
//         $this->attributes['code'] = "HS".$this->getID();
//
//     }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function getOwnTime()
    {
        $daily = [];
        $grades = $this->Grades()->where("status", 0)->where("time", "!=", null)->get();
        $index = 0;
        foreach ($grades as $grade) {
            $time = $grade->time;
            foreach ($time as $day) {
                $daily[$day["day"]][$index]["value"] = $day["value"];
                $daily[$day["day"]][$index]["grade"] = $grade;
                $index++;
            }
        }
        return $daily;
    }

    public function Detail()
    {
        return view("components.detail", ['route' => route("admin.student.detail", $this->id)]);
    }

    public function Grades()
    {
        return $this->belongsToMany(Grade::class, "student_grade", "student_id", "grade_id");
    }

    public function Staffs()
    {
        $staff = [];
        $grades = $this->Grades()->get();
        foreach ($grades as $grade) {
            try {
                if (!in_array($grade->Staff()->first()->name, $staff)) {
                    $staff[] = $grade->Staff()->first()->name;
                }
            }
        }
        $staff = implode(",", $staff);
        if ($staff != null) {
            return $staff;
        } else {
            return "-";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
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
    protected
        $casts = [
        'email_verified_at' => 'datetime',
        'extra' => 'json',
    ];

    public function setPrivate()
    {
        $this->attributes['private_key'] = Hash::make($this->name . $this->code);
    }
}
