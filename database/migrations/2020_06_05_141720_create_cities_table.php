<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCitiesTable
 */
class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siruta_id');
            $table->unsignedBigInteger('siruta_parent_id')->nullable();
            $table->string('name', 255)->index();

            $table->foreign('siruta_id')
                ->references('SIRUTA')
                ->on('siruta');

            $table->foreign('siruta_parent_id')
                ->references('SIRUTA')
                ->on('siruta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
