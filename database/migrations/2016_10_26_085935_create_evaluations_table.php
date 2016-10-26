<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id')->references('id')->on('shops');
            $table->unsignedInteger('user_id')->references('id')->on('users');
            $table->unsignedInteger('ad_id')->references('id')->on('ads');
            $table->enum('status', ['given', 'pending', 'forced']);
            $table->boolean('is_positive');
            $table->text('body')->nullable();
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
        Schema::dropIfExists('evaluations');
    }
}
