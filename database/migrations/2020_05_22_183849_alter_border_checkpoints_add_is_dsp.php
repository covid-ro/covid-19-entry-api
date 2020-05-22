<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBorderCheckpointsAddIsDsp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('border_checkpoints', function (Blueprint $table) {
            $table->boolean('is_dsp_before_border')->default(false)->after('name')->index();
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
            $table->dropColumn('is_dsp_before_border');
        });
    }
}
