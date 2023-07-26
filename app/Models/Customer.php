<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

class Customer extends Model
{
    use HasFactory;
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'extra' => 'json',
    ];

    public function getID()
    {
        return $this->id ?? Customer::max("id") + 1;
    }

    public function setPasswordAttribute($value)
    {
        if ($value != "") {
            $this->attributes['password'] = Hash::make($value);
        }
    }
//    public function setCodeAttribute() {
//        $this->attributes['code'] = "KH".$this->getID();
//
//    }
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
        return $this->belongsToMany(Grade::class, "student_grade", "student_id", "grade_id")->withoutGlobalScopes();
    }

    public function Switch()
    {
        return view("components.switcher", ['route' => route("admin.customer.switch", $this->id)]);
    }

    public function setPrivate()
    {
        $this->attributes['private_key'] = Hash::make($this->name . $this->code);
    }

    public function originStaff()
    {
        return $this->belongsTo(Staff::class, "staff_id", "id");
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

    public function Contests(): BelongsToMany
    {
        return $this->belongsToMany(Contest::class, "customer_contest", "customer_id", "contest_id")
            ->withPivot(["score", "correct", "total", "correct_task"]);
    }
}
