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
        Schema::create('customer_demo', function (Blueprint $table) {
            $table->unsignedBigInteger("customer_id");
            $table->foreign("customer_id")->references("id")->on("users");
            $table->unsignedBigInteger("demo_id");
            $table->foreign("demo_id")->references("id")->on("demos");
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
        Schema::dropIfExists('customer_demo');
    }
};
