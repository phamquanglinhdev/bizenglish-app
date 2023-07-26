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
        Schema::create('customer_contest', function (Blueprint $table) {
            $table->id();
            $table->integer("customer_id");
            $table->integer("contest_id");
            $table->integer("score")->nullable();
            $table->integer("correct")->nullable();
            $table->integer("total")->nullable();
            $table->longText("correct_task")->nullable();
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
        Schema::dropIfExists('customer_contest');
    }
};
