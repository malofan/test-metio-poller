<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sensor_id');
            $table->date('date');
            $table->unsignedInteger('city_id');
            $table->integer('day_temperature');
            $table->integer('night_temperature');
            $table->integer('day_humidity');
            $table->integer('night_humidity');

            $table->index('date');
            $table->unique(['sensor_id', 'date', 'city_id']);
            $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sensor_datas', function (Blueprint $table) {
            $table->dropForeign(['sensor_id']);
            $table->dropIndex(['date']);
            $table->dropUnique(['sensor_id', 'date', 'city_id']);
        });
        Schema::dropIfExists('sensor_datas');
    }
}
