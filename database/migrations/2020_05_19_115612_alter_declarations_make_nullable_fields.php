<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeclarationsMakeNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->boolean('q_visited')->nullable()->change();
            $table->boolean('q_contacted')->nullable()->change();
            $table->boolean('q_hospitalized')->nullable()->change();
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
            $table->boolean('q_visited')->change();
            $table->boolean('q_contacted')->change();
            $table->boolean('q_hospitalized')->change();
        });
    }
}
