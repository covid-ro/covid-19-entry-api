<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsAddIsRomanian
 */
class AlterDeclarationsAddIsRomanian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function(Blueprint $table) {
            $table->boolean('is_romanian')->default(false)->after('cnp')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('declarations', function(Blueprint $table) {
            $table->dropColumn('is_romanian');
        });
    }
}
