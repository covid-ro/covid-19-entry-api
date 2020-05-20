<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeclarationCodesIncreaseCodeSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declaration_codes', function (Blueprint $table) {
            $table->dropIndex('codes_code_unique');
            $table->string('code', 7)->unique()->index()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('declaration_codes', function (Blueprint $table) {
            $table->dropIndex('declaration_codes_code_unique');
            $table->string('code', 6)->unique()->index()->change();
        });
    }
}
