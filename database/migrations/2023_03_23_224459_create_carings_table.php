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
        Schema::create('carings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("staff_id");
            $table->unsignedBigInteger("student_id");
//            $table->foreign("staff_id")->references("id")->on("users");
//            $table->foreign("student_id")->references("id")->on("users");
            $table->string("note");
            $table->string("origin")->default("bizsoft");
            $table->date("date")->nullable();
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
        Schema::dropIfExists('carings');
    }
};
