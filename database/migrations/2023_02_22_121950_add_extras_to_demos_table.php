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
        Schema::table('demos', function (Blueprint $table) {
            $table->string("student_phone")->nullable();
            $table->string("student_facebook")->nullable();
            $table->unsignedBigInteger("staff_id")->nullable();
            $table->unsignedBigInteger("supporter_id")->nullable();
            $table->foreign("staff_id")->references("id")->on("users");
            $table->foreign("supporter_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demos', function (Blueprint $table) {
            $table->dropColumn(["student_phone", "student_facebook", "staff_id", "supporter_id"]);
        });
    }
};
