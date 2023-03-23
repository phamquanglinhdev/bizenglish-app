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
        if (Student::where("type", 3)->where("disable", 0)->orderBy("code", "DESC")->count() != 0) {
            $student = Student::where("type", 3)->where("code", "like", "HV%")->where("disable", 0)->orderBy("code", "DESC")->first();
            $code = str_replace("HV", "", $student->code);
            $code += 1;
            if ($code < 100) {
                if ($code < 10) {
                    $code = "HV00$code";
                } else {
                    $code = "HV0$code";
                }
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
    public function Carings()
    {
        return $this->hasMany(Caring::class, "student_id", "id");
    }

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

    public function Detail()
    {
        return view("components.detail", ['route' => route("admin.student.detail", $this->id)]);
    }

    public function Grades()
    {
        return $this->belongsToMany(Grade::class, "student_grade", "student_id", "grade_id");
    }

    public function originStaff()
    {
        return $this->belongsTo(Staff::class, "staff_id", "id");
    }

    public function Supporters()
    {
        $staff = [];
        $grades = $this->Grades()->get();
        foreach ($grades as $grade) {
            try {
                if (!in_array($grade->Supporter()->first()->name, $staff)) {
                    $staff[] = $grade->Supporter()->first()->name;
                }
            } catch (\Exception $exception) {

            }
        }
        $staff = implode(",", $staff);
        if ($staff != null) {
            return $staff;
        } else {
            return "-";
        }
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
            } catch (\Exception $exception) {

            }
        }
        $staff = implode(",", $staff);
        if ($staff != null) {
            return $staff;
        } else {
            return $this->originStaff()->first()->name ?? "-";
        }
    }

    public function calendar()
    {
        $calendar = new \stdClass();
        $calendar->monday = [];
        $calendar->tuesday = [];
        $calendar->wednesday = [];
        $calendar->thursday = [];
        $calendar->friday = [];
        $calendar->saturday = [];
        $calendar->sunday = [];
        $grades = $this->Grades()->get();
        foreach ($grades as $grade) {
            $times = $grade->time;
            foreach ($times as $time) {
                $item = new \stdClass();
                $item->id = $grade->id;
                $item->name = $grade->name;
                $item->value = $time["value"];
                switch ($time["day"]) {
                    case "mon":
                        $calendar->monday[] = $item;
                        break;
                    case "tue":
                        $calendar->tuesday[] = $item;
                        break;
                    case "wed":
                        $calendar->wednesday[] = $item;
                        break;
                    case "thu":
                        $calendar->thursday[] = $item;
                        break;
                    case "fri":
                        $calendar->friday[] = $item;
                        break;
                    case "sat":
                        $calendar->saturday[] = $item;
                        break;
                    case "sun":
                        $calendar->sunday[] = $item;
                        break;
                }

            }
        }
        return $calendar;
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
