<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLatLongAttributesNamesInAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!env('DB_CONNECTION') == 'sqlite') {
            DB::statement("ALTER TABLE ads CHANGE `lat` `latitude` float");
            DB::statement("ALTER TABLE ads CHANGE `long`  `longitude` float");
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
            DB::statement("ALTER TABLE ads CHANGE `latitude`  `lat` float");
            DB::statement("ALTER TABLE ads CHANGE `longitude`  `long` float");
        }
    }
}
