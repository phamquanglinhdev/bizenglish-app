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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("grade_id");
            $table->foreign("grade_id")->references("id")->on("grades");
            $table->integer("day");
            $table->integer("month");
            $table->integer("year");
            $table->integer("hour");
            $table->integer("minutes");
            $table->integer("duration");
            $table->string("lesson");
            $table->string("information");
            $table->integer("hour_salary");
            $table->string("teacher_video");
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
        Schema::dropIfExists('logs');
    }
};
