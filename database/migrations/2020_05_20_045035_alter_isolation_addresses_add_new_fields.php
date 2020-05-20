<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterIsolationAddressesAddNewFields
 */
class AlterIsolationAddressesAddNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isolation_addresses', function (Blueprint $table) {
            $table->dropColumn('city_full_address');

            $table->string('street', 64)->nullable()->after('county');
            $table->string('number', 16)->nullable()->after('street');
            $table->string('bloc', 16)->nullable()->after('number');
            $table->string('entry', 16)->nullable()->after('bloc');
            $table->string('apartment', 16)->nullable()->after('entry');


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
            $table->string('city_full_address', 256)->change();

            $table->dropColumn('street');
            $table->dropColumn('number');
            $table->dropColumn('bloc');
            $table->dropColumn('entry');
            $table->dropColumn('apartment');
        });
    }
}
