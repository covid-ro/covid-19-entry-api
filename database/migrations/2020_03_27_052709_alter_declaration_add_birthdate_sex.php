<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationAddBirthdateSex
 */
class AlterDeclarationAddBirthdateSex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->date('birth_date')->after('cnp')->nullable()->index();
            $table->string('sex', 1)->after('birth_date')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn('birth_date');
            $table->dropColumn('sex');
        });
    }
}
