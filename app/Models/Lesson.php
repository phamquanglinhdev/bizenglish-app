<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use ZanySoft\Zip\Zip;
class Lesson extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lessons';
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
//    public function setPDFAttribute($value)
//    {
////        $attribute_name = "pdf";
////        $disk = "uploads_document";
////        $destination_path = "";
////
////        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
////        \Illuminate\Support\Facades\Log::alert($value);
//        $zip = Zip::open($value);
//        $zip->extract('/public/upload/uncompressed/files');
//        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
//    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
