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
        Schema::create('teacher_comment', function (Blueprint $table) {
            $table->unsignedBigInteger("teacher_id");
            $table->unsignedBigInteger("comment_id");
            $table->foreign("teacher_id")->references("id")->on("users");
            $table->foreign("comment_id")->references("id")->on("comments");
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
        Schema::dropIfExists('teacher_comment');
    }
};
