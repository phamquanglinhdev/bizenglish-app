<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
    protected $casts = [
        'time' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function meeting(): string
    {
        return '<a href="'.backpack_url("/meet/$this->id").'" class="btn btn-sm btn-link"><i class="la la-chess-board"></i>Phòng học(Thử nghiệm)</a>';
    }
    public function afterStore()
    {
//        if (backpack_user() == 0) {
//            DB::table("supporter_grade")->where("supporter_id", "=", backpack_user()->id)
//                ->where("grade_id", "=", backpack_user()->id)->count()==0;
//            DB::table("staff_grade")->where("grade_id", "=", $this->id)->delete();
//            DB::table("staff_grade")->insert([
//                "staff_id" => backpack_user()->id,
//                "grade_id" => $this->id,
//            ]);
//        }
    }

    public function getStatus()
    {
        $status = ["Đang học", "Đã kết thúc", "Đang bảo lưu"];
        return $status[$this->attributes["status"]];
    }

    public function fewDates(): bool
    {
        $durations = $this->Logs()->sum("duration");
        return ($this->minutes) - $durations > 60;
    }

    public function getRs()
    {
        $durations = $this->Logs()->sum("duration");
        return ($this->minutes) - $durations;
    }

    public function percentCount(): float|int
    {
        $durations = $this->Logs()->sum("duration");
        return $durations / $this->minutes * 100;
    }

    public function toIndex()
    {
        return redirect("admin");
    }

    public function identify()
    {
        return $this->name;
    }
//    public function setAttachmentAttribute($value)
//    {
//        $attribute_name = "attachment";
//        $disk = "uploads_document";
//        $destination_path = "";
//
//        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
//
//        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
//    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function Student()
    {
        return $this->belongsToMany(User::class, "student_grade", "grade_id", "student_id")->where("disable", 0);
    }

    public function Teacher()
    {
        return $this->belongsToMany(User::class, "teacher_grade", "grade_id", "teacher_id");
    }

    public function Client()
    {
        return $this->belongsToMany(User::class, "client_grade", "grade_id", "client_id");
    }

    public function Staff(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, "staff_grade", "grade_id", "staff_id");
    }

    public function Supporter(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, "supporter_grade", "grade_id", "supporter_id");
    }

    public function Logs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Log::class, "grade_id", "id");
    }
    public function Menus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Menu::class,"grade_menus","grade_id","menu_id");
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOwner($query)
    {
        $grades = DB::table("staff_grade")->where("staff_id", backpack_user()->id)->get();
        $subGrades = DB::table("supporter_grade")->where("supporter_id", backpack_user()->id)->get();
        if ($grades->count() > 0 || $subGrades->count() > 0) {
            $query->where("id", -1);
            foreach ($grades as $grade) {
                $query->orWhere("id", $grade->grade_id);
            }
            foreach ($subGrades as $grade) {
                $query->orWhere("id", $grade->grade_id);
            }
        } else {

            $query->where("id", -1);
        }
        return $query;


    }

    public function isNotSupporter($gradeId)
    {
        return DB::table("supporter_grade")
                ->where("grade_id", "=", $gradeId)
                ->where("supporter_id", "=", backpack_user()->id)->count() == 0;
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
