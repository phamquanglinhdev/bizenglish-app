<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_grade', function (Blueprint $table) {
            $table->unsignedBigInteger("teacher_id");
            $table->foreign("teacher_id")->references("id")->on("users");
            $table->unsignedBigInteger("grade_id");
            $table->foreign("grade_id")->references("id")->on("grades");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_grade');
    }
};
