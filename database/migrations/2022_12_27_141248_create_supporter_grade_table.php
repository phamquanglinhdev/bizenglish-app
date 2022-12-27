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
        Schema::create('supporter_grade', function (Blueprint $table) {
            $table->unsignedBigInteger("supporter_id");
            $table->unsignedBigInteger("grade_id");
            $table->foreign("supporter_id")->references("id")->on("users");
            $table->foreign("grade_id")->references("id")->on("grades");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supporter_grade');
    }
};
