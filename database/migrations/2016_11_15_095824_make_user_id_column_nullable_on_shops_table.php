<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserIdColumnNullableOnShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            DB::statement('ALTER TABLE `shops` MODIFY `user_id` INTEGER UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            DB::statement('ALTER TABLE `shops` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');

            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
