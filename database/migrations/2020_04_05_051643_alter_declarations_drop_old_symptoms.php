<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsDropOldSymptoms
 */
class AlterDeclarationsDropOldSymptoms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn('symptom_fever');
            $table->dropColumn('symptom_swallow');
            $table->dropColumn('symptom_breathing');
            $table->dropColumn('symptom_cough');
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
            $table->boolean('symptom_fever');
            $table->boolean('symptom_swallow');
            $table->boolean('symptom_breathing');
            $table->boolean('symptom_cough');
        });
    }
}
