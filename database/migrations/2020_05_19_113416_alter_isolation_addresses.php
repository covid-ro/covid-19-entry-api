<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterIsolationAddresses
 */
class AlterIsolationAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isolation_addresses', function (Blueprint $table) {
            $table->date('city_arrival_date')->nullable()->change();
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
            $table->date('city_arrival_date')->change();
        });
    }
}
