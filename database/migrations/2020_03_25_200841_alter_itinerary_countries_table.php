<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterItineraryCountriesTable
 */
class AlterItineraryCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itinerary_countries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->unsignedBigInteger('declaration_id')->after('id');
            $table->foreign('declaration_id')
                ->references('id')
                ->on('declarations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itinerary_countries', function (Blueprint $table) {
            $table->dropForeign(['declaration_id']);
            $table->dropColumn('declaration_id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }
}
