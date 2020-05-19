<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsAddAcceptFields
 */
class AlterDeclarationsAddAcceptFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->boolean('accept_personal_data')->after('dsp_user_name')->default(false);
            $table->boolean('accept_read_law')->after('accept_personal_data')->default(false);
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
            $table->dropColumn('accept_personal_data');
            $table->dropColumn('accept_read_law');
        });
    }
}
