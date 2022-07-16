<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exercise extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'exercises';
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
    public function Log()
    {
        return $this->belongsTo(Log::class, "log_id", "id");
    }
    public function Grade(){
        $grade = $this->Log()->first()->Grade()->first()->name;
//        return $this->Log()->first()->belongsTo(Grade::class,"grade_id","id");
        return $grade;
    }
    public function Student(){
        return $this->belongsTo(Student::class,"student_id","id");
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function setVideoAttribute($value)
    {
        $attribute_name = "video";
        $disk = "uploads_video";
        $destination_path = "";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function setDocumentAttribute($value)
    {
        $attribute_name = "document";
        $disk = "uploads_document";
        $destination_path = "";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeRep($query)
    {
        $logs = Teacher::find(backpack_user()->id)->Logs()->get();
        if ($logs->count() > 0) {
            $query->where("log_id", $logs->first()->id);
            foreach ($logs as $key => $log) {
                $query = $query->orWhere("log_id", $log->id);
            }
        } else {
            $query->where("id", -1);
        }
        return $query;


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
