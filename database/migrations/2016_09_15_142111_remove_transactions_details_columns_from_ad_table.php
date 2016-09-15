<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTransactionsDetailsColumnsFromAdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropForeign('ads_sold_to_foreign');
            $table->dropForeign('ads_locked_for_foreign');
            $table->dropColumn(['transaction', 'locked_for', 'is_secure_payment', 'is_locked', 'sold_to', 'is_hidden_from_friends']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->unsignedInteger('locked_for')->nullable()->default(null);
            $table->boolean('is_secure_payment')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('sold_to')->unsigned()->nullable();
            $table->boolean('is_hidden_from_friends')->unsigned()->default(0);
            $table->json('transaction')->nullable();

            $table->foreign('sold_to')->references('id')->on('users');
            $table->foreign('locked_for')->references('id')->on('users');
        });
    }
}
