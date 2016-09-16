<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('seller_id')->nullable();
            $table->json('stripe_data')->nullable();
            $table->json('details')->nullable();
            
            // Define timestamps to record dates and times of changes.
            $table->timestamps();

            // Indices and foreign keys
            $table->foreign('ad_id')->references('id')->on('ads');
            $table->foreign('buyer_id')->references('id')->on('users');
            $table->foreign('seller_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
