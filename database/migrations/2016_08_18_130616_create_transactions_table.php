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

            if(env('DB_CONNECTION') == 'sqlite') {
                $table->unsignedInteger('buyer_id')->nullable()->after('ad_id');
                $table->unsignedInteger('seller_id')->nullable()->after('buyer_id');
                $table->json('stripe_data')->nullable()->after('seller_id');
                $table->json('details')->nullable()->after('stripe_data');

                $table->foreign('buyer_id')->references('id')->on('users');
                $table->foreign('seller_id')->references('id')->on('users');
            } else {
                $table->unsignedInteger('user_id');
                $table->text('data');
                $table->foreign('user_id')->references('id')->on('users');
            }

            // Define timestamps to record dates and times of changes.
            $table->timestamps();

            // Indices and foreign keys
            $table->foreign('ad_id')->references('id')->on('ads');
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
