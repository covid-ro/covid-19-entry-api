<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSettlementsTable
 */
class CreateSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siruta_id');
            $table->unsignedBigInteger('siruta_parent_id');
            $table->unsignedBigInteger('county_id');
            $table->string('name', 255)->index();

            $table->foreign('siruta_id')
                ->references('SIRUTA')
                ->on('siruta');

            $table->foreign('county_id')
                ->references('id')
                ->on('counties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlements');
    }
}
