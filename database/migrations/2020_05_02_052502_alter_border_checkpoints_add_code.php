<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterBorderCheckpointsAddCode
 */
class AlterBorderCheckpointsAddCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('border_checkpoints', function (Blueprint $table) {
            $table->string('code', 32)->after('id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('border_checkpoints', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
