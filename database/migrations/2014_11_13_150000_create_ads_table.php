<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->unsignedInteger('shop_id')->nullable();
            $table->unsignedInteger('initial_quantity');
            $table->unsignedInteger('remaining_quantity');
            $table->integer('category_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->bigInteger('amount');
            $table->bigInteger('final_amount')->nullable()->default(null);
            $table->string('currency')->default('USD');
            $table->json('delivery')->nullable();
            $table->text('location');
            $table->float('latitude', 10, 6)->nullable();
            $table->float('longitude', 10, 6)->nullable();
            $table->json('images');
            $table->integer('condition_id')->unsigned();

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Foreign keys and indices
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ads');
    }

}
