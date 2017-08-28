<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineSurveydataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_surveydata', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('machine_id')->unsigned()->nullable();
            $table->boolean('gendata')->nullable();
            $table->boolean('collimatordata')->nullable();
            $table->boolean('radoutputdata')->nullable();
            $table->boolean('radsurveydata')->nullable();
            $table->boolean('hvldata')->nullable();
            $table->boolean('fluorodata')->nullable();
            $table->boolean('maxfluorodata')->nullable();
            $table->boolean('receptorentrance')->nullable();
            $table->index(['machine_id', 'id']);
            $table->softDeletes();
            $table->timestamps();
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
