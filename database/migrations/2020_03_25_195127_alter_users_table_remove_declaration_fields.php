<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AlterUsersTableRemoveDeclarationFields
 */
class AlterUsersTableRemoveDeclarationFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('surname');
            $table->dropColumn('email');
            $table->dropColumn('cnp');
            $table->dropColumn('document_type');
            $table->dropColumn('document_series');
            $table->dropColumn('document_number');
            $table->dropColumn('travelling_from_country_code');
            $table->dropColumn('travelling_from_city');
            $table->dropColumn('travelling_from_date');
            $table->dropColumn('home_country_return_date');
            $table->dropColumn('question_1_answer');
            $table->dropColumn('question_2_answer');
            $table->dropColumn('question_3_answer');
            $table->dropColumn('symptom_fever');
            $table->dropColumn('symptom_swallow');
            $table->dropColumn('symptom_breathing');
            $table->dropColumn('symptom_cough');
            $table->dropColumn('vehicle_type');
            $table->dropColumn('vehicle_registration_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 64)->nullable();
            $table->string('surname', 64)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('cnp', 13)->nullable();
            $table->string('document_type', 16)->nullable();
            $table->string('document_series', 16)->nullable();
            $table->string('document_number', 32)->nullable();
            $table->string('travelling_from_country_code', 2)->nullable();
            $table->string('travelling_from_city', 32)->nullable();
            $table->date('travelling_from_date')->nullable();
            $table->date('home_country_return_date')->nullable();
            $table->string('question_1_answer', 512)->nullable();
            $table->string('question_2_answer', 512)->nullable();
            $table->string('question_3_answer', 512)->nullable();
            $table->boolean('symptom_fever')->default(false);
            $table->boolean('symptom_swallow')->default(false);
            $table->boolean('symptom_breathing')->default(false);
            $table->boolean('symptom_cough')->default(false);
            $table->string('vehicle_type', 16)->nullable();
            $table->string('vehicle_registration_no', 16)->nullable();
        });
    }
}
