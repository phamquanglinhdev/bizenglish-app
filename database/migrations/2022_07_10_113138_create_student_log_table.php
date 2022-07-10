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
        Schema::create('student_log', function (Blueprint $table) {
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("log_id");
            $table->integer("accept");
            $table->longText("comment")->nullable();
            $table->foreign("student_id")->references("id")->on("users");
            $table->foreign("log_id")->references("id")->on("logs");
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
        Schema::dropIfExists('student_log');
    }
};
