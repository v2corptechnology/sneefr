<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCategoryFromAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropForeign('ads_category_id_foreign');
            $table->dropColumn('category_id');
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
            $table->integer('category_id')->unsigned()->after('remaining_quantity');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
}
