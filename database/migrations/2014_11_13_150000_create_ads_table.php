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
            $table->integer('category_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->bigInteger('amount');
            $table->bigInteger('final_amount')->nullable()->default(null);
            $table->string('currency')->default('USD');
            $table->json('delivery')->nullable();
            $table->json('transaction')->nullable();
            $table->text('location');
            $table->float('lat', 10, 6)->nullable();
            $table->float('long', 10, 6)->nullable();
            $table->json('images');
            $table->unsignedInteger('locked_for')->nullable()->default(null);
            $table->boolean('is_secure_payment')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('sold_to')->unsigned()->nullable();
            $table->integer('condition_id')->unsigned();
            $table->boolean('is_hidden_from_friends')->unsigned()->default(0);

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Foreign keys and indices
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sold_to')->references('id')->on('users');
            $table->foreign('locked_for')->references('id')->on('users');
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
