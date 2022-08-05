<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    protected $guarded = ['id'];
    // protected $fillable = [];
//     protected $hidden = ['password'];
    // protected $dates = [];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'extra' => 'json',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    // public function setCodeAttribute() {
    //     $this->attributes['code'] = "KH".$this->id;
    // }
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
    public function Switch(){
        return view("components.switcher", ['route' => route("admin.customer.switch", $this->id)]);
    }
}
