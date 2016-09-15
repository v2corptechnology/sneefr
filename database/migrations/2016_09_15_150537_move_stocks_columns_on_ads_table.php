<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveStocksColumnsOnAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!env('DB_CONNECTION') == 'sqlite') {
            Schema::table('ads', function (Blueprint $table) {
                $table->unsignedInteger('initial_quantity')->after('shop_id');
                $table->unsignedInteger('remaining_quantity')->after('initial_quantity');
            });

            Schema::drop('stocks');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!env('DB_CONNECTION') == 'sqlite') {
            Schema::table('ads', function (Blueprint $table) {
                $table->dropColumn(['initial_quantity', 'remaining_quantity']);
            });

            Schema::create('stocks', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('ad_id')->references('id')->on('ads');
                $table->unsignedInteger('initial');
                $table->unsignedInteger('remaining');
                $table->timestamps();
            });
        }
    }
}
