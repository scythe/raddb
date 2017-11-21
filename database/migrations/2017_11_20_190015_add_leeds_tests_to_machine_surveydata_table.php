<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeedsTestsToMachineSurveydataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add flags for Leeds test objects test data
        Schema::table('machine_surveydata', function (Blueprint $table) {
            $table->boolean('leeds_n3')->nullable()->default(null)->after('receptorentrance');
            $table->boolean('leeds_to10')->nullable()->default(null)->before('deleted_at');
            $table->boolean('fluoro_resolution')->nullable()->default(null)->before('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
