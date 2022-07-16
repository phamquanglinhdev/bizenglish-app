<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';
    protected $guarded = ["id"];

    public function Teachers()
    {
        return $this->belongsToMany(Teacher::class, "teacher_skill", "skill_id", "teacher_id");
    }
}
