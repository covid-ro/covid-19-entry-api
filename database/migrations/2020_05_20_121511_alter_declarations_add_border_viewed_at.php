<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsAddBorderViewedAt
 */
class AlterDeclarationsAddBorderViewedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->index('cnp');

            $table->timestamp('border_viewed_at')->nullable()->after('border_validated_at');
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
            $table->dropIndex(['cnp']);
            $table->dropColumn('border_viewed_at');
        });
    }
}
