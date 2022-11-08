<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'times';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'data' => 'json'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function RedirectToEdit()
    {
        $id = Teacher::where("id", "=", backpack_user()->id)->first()->Time()->first()->id;

        return redirect("/admin/time/$id/show");
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function Teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_id", "id");
    }

    public function ArrMorning()
    {
        $morning = $this->morning;

    }

    public static function ArrToString($matrix)
    {
        $result = [];
        foreach ($matrix as $row) {
            $result [] = implode("*", $row);
        }
        return implode("@", $result);
    }

    public static function StringToArr($string)
    {
        $result = explode("@", $string);
        foreach ($result as $index => $item) {
            $item = explode("*", $item);
            $result[$index] = $item;
        }
        return $result;
    }

    public function getMorningArr()
    {
        return Time::StringToArr($this->morning);
    }
    public function getAfternoonArr()
    {
        return Time::StringToArr($this->afternoon);
    }
    public function getEveningArr()
    {
        return Time::StringToArr($this->evening);
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
}
