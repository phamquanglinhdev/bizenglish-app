<?php

namespace App\Models;

use App\Models\Scopes\CurrentGradeScope;
use App\Models\Scopes\GradeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Grade extends Model
{
    use CrudTrait;

    public int $fixColumn = 1;
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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new GradeScope);
        static::addGlobalScope(new CurrentGradeScope);
    }

    protected static function subBoot()
    {
        parent::boot();
        static::addGlobalScope(new GradeScope);
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function meeting(): string
    {
        return '<a href="' . backpack_url("/meet/$this->id") . '" class="btn btn-sm btn-link"><i class="la la-chess-board"></i>Phòng học(Thử nghiệm)</a>';
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
        $status = [
            0 => "Đang học",
            1 => "Đã kết thúc",
            2 => "Đang bảo lưu"
        ];

        if (! in_array($this->attributes['status'], array_keys($status))) {
            return  "";
        }

        return $status[$this->attributes['status']] ?? "";
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
        if ($durations != 0) {
            return $durations / $this->minutes * 100;
        } else {
            return 0;
        }
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
        return $this->belongsToMany(Student::class, "student_grade", "grade_id", "student_id")->where("disable", 0);
    }

    public function Teacher()
    {
        return $this->belongsToMany(Teacher::class, "teacher_grade", "grade_id", "teacher_id");
    }

    public function Client()
    {
        return $this->belongsToMany(Client::class, "client_grade", "grade_id", "client_id");
    }

    public function Staff(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Staff::class, "staff_grade", "grade_id", "staff_id");
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
        return $this->belongsToMany(Menu::class, "grade_menus", "grade_id", "menu_id");
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

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
