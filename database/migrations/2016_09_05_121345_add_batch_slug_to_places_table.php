<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddBatchSlugToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(env('DB_CONNECTION') == 'sqlite') {
            DB::update("update places set slug = '@' || places.latitude || ',' || places.longitude");
        } else {
            DB::update("update places set slug = concat('@', places.latitude,',', places.longitude)");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
