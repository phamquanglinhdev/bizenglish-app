<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->date("date");
            $table->string("start");
            $table->string("end");
            $table->integer("duration");
            $table->string("lesson");
            $table->longText("information");
            $table->integer("hour_salary");
            $table->integer("log_salary")->nullable();
            $table->longText("teacher_video")->nullable();
            $table->integer("disable")->default(0);
            $table->longText("status")->nullable();
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
