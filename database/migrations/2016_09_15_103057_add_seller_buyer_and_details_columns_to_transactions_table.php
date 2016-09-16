<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSellerBuyerAndDetailsColumnsToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if(!env('DB_CONNECTION') == 'sqlite') {
                $table->dropForeign('transactions_user_id_foreign');
                $table->dropColumn(['user_id', 'data']);

                $table->unsignedInteger('buyer_id')->nullable()->after('ad_id');
                $table->unsignedInteger('seller_id')->nullable()->after('buyer_id');
                $table->json('stripe_data')->nullable()->after('seller_id');
                $table->json('details')->nullable()->after('stripe_data');

                $table->foreign('buyer_id')->references('id')->on('users');
                $table->foreign('seller_id')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if(!env('DB_CONNECTION') == 'sqlite') {
                $table->dropForeign('transactions_buyer_id_foreign');
                $table->dropForeign('transactions_seller_id_foreign');
                $table->dropColumn(['buyer_id', 'seller_id', 'details']);

                $table->unsignedInteger('user_id')->after('id');
                $table->text('data')->after('user_id');
                $table->foreign('user_id')->references('id')->on('users');
            }
        });
    }
}
