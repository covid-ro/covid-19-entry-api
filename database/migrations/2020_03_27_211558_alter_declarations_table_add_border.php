<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsTableAddBorder
 */
class AlterDeclarationsTableAddBorder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->unsignedBigInteger('border_checkpoint_id')->after('declarationcode_id')->nullable();

            $table->foreign('border_checkpoint_id')
                ->references('id')
                ->on('border_checkpoints');
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
            $table->dropForeign(['border_checkpoint_id']);
            $table->dropColumn('border_checkpoint_id');
        });
    }
}
