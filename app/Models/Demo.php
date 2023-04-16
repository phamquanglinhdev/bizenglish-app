<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'demos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'status' => 'json',
        'attachments' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function StatusShow()
    {
        if ($this->status != null) {
            $status = $this->status[0];
            $time = $status["time"];
            $name = $status["name"] * 1;
            switch ($name * 1) {
                case 0:
                    return "Học viên và giáo viên vào đúng giờ.";
                case 1:
                    return "Học viên vào muộn $time phút";
                case 2:
                    return "Giáo viên vào muộn $time phút";
                case 3:
                    return "Học viên hủy buổi học trước $time giờ";
                case 4:
                    return "Giáo viên hủy buổi học trước $time giờ";
                default:
                    return $status["message"];
            }
        }

    }    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function Teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_id", "id");
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, "client_id", "id");
    }

    public function Staff()
    {
        return $this->belongsTo(Staff::class, "staff_id", "id");
    }

    public function Supporter()
    {
        return $this->belongsTo(Staff::class, "supporter_id", "id");
    }

    public function Customers()
    {
        return $this->belongsToMany(Customer::class, "customer_demo", "demo_id", "customer_id");
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function setAttachmentsAttribute($value)
    {
        $attribute_name = "attachments";
        $disk = "uploads_document";
        $destination_path = "/";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
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
