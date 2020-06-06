<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterIsolationAddressesAddSiruta
 */
class AlterIsolationAddressesAddSiruta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isolation_addresses', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('county');

            $table->unsignedBigInteger('county_id')->after('declaration_id')->nullable();
            $table->unsignedBigInteger('settlement_id')->after('county_id')->nullable();

            $table->foreign('county_id')
                ->references('id')
                ->on('counties');

            $table->foreign('settlement_id')
                ->references('id')
                ->on('settlements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isolation_addresses', function (Blueprint $table) {
            $table->string('city', 64)->after('declaration_id');
            $table->string('county', 64)->index()->after('city');

            $table->dropForeign(['county_id']);
            $table->dropColumn('county_id');
            $table->dropForeign(['settlement_id']);
            $table->dropColumn('settlement_id');
        });
    }
}
