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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText("message");
            $table->unsignedBigInteger("inherit_id")->nullable();
            $table->foreign("inherit_id")->references("id")->on("comments");
            $table->unsignedBigInteger("log_id");
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
        Schema::dropIfExists('comments');
    }
};
