<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSirutaMakeFieldNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Update `siruta` entries
         */
        Schema::table('siruta', function (Blueprint $table) {
            $table->unsignedBigInteger('ULT')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * Update `siruta` entries
         */
        Schema::table('siruta', function (Blueprint $table) {
            $table->unsignedBigInteger('ULT')->change();
        });
    }
}
