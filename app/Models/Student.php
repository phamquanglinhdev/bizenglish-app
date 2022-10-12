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
    protected $guarded = [];
    // protected $fillable = [];
//     protected $hidden = ['password'];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getID()
    {
        return $this->id ?? Student::max("id") + 1;
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
            if (!in_array($grade->Staff()->first()->name, $staff)) {
                $staff[] = $grade->Staff()->first()->name;
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
        $this->attributes['private_key'] = \Illuminate\Support\Str::random(15);
    }
}
