<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsRenameCodeId
 */
class AlterDeclarationsRenameCodeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropForeign(['code_id']);
            $table->renameColumn('code_id', 'declarationcode_id');

            $table->foreign('declarationcode_id')
                ->references('id')
                ->on('declaration_codes');
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
            $table->dropForeign(['declarationcode_id']);

            $table->renameColumn('declarationcode_id', 'code_id');

            $table->foreign('code_id')
                ->references('id')
                ->on('codes');
        });
    }
}
