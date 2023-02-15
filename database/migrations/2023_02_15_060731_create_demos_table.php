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
        Schema::create('demos', function (Blueprint $table) {
            $table->id();
            $table->string("grade");
            $table->string("students");
            $table->unsignedBigInteger("teacher_id");
            $table->unsignedBigInteger("client_id");
            $table->foreign("teacher_id")->references("id")->on("users");
            $table->foreign("client_id")->references("id")->on("users");
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
            $table->longText("question")->nullable();
            $table->longText("assessment")->nullable();
            $table->longText("attachments")->nullable();
            $table->string("drive")->nullable();
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
        Schema::dropIfExists('demos');
    }
};
