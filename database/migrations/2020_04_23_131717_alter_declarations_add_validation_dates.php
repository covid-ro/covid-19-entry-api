<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsAddValidationDates
 */
class AlterDeclarationsAddValidationDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function(Blueprint $table) {
            $table->timestamp('border_validated_at')->nullable()->after('status');
            $table->timestamp('dsp_validated_at')->nullable()->after('border_validated_at');
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
            $table->dropColumn('border_validated_at');
            $table->dropColumn('dsp_validated_at');
        });
    }
}
