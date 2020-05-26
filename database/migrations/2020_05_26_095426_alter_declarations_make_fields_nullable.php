<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsMakeFieldsNullable
 */
class AlterDeclarationsMakeFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function(Blueprint $table) {
            $table->string('email', 255)->nullable()->change();
            $table->string('document_type', 16)->nullable()->change();
            $table->string('document_series', 16)->nullable()->change();
            $table->string('document_number', 32)->nullable()->change();
            $table->string('travelling_from_city', 32)->nullable()->change();
            $table->string('vehicle_type', 16)->nullable()->change();
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
            $table->string('email', 255)->change();
            $table->string('document_type', 16)->change();
            $table->string('document_series', 16)->change();
            $table->string('document_number', 32)->change();
            $table->string('travelling_from_city', 32)->change();
            $table->string('vehicle_type', 16)->change();
        });
    }
}
