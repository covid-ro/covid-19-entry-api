<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterDeclarationsRenameFields
 */
class AlterDeclarationsRenameFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declarations', function(Blueprint $table) {
            $table->dropColumn('question_1_answer');
            $table->dropColumn('question_2_answer');
            $table->dropColumn('question_3_answer');

            $table->boolean('q_visited')->after('home_country_return_date');
            $table->boolean('q_contacted')->after('q_visited');
            $table->boolean('q_hospitalized')->after('q_contacted');
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
            $table->dropColumn('q_visited');
            $table->dropColumn('q_contacted');
            $table->dropColumn('q_hospitalized');

            $table->string('question_1_answer', 512)->change();
            $table->string('question_2_answer', 512)->change();
            $table->string('question_3_answer', 512)->change();
        });
    }
}
