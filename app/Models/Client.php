<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Client extends Model
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
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function setPasswordAttribute($value)
    {
        if ($value != "") {
            $this->attributes['password'] = Hash::make($value);
        }
    }

//    public function setCodeAttribute()
//    {
//        $this->attributes['code'] = "DT" . $this->id;
//    }

    public function Grades()
    {
        return $this->belongsToMany(Grade::class, "client_grade", "client_id", "grade_id")->withoutGlobalScopes();
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function getOwnTime()
    {
        $daily = [];
        $grades = $this->Grades()->where("disable", 0)->where("status", 0)->where("time", "!=", null)->get();
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'extra' => 'json',
    ];
}
