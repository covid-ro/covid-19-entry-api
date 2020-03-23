<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 32)->index();
            $table->string('country_code', 2)->nullable();
            $table->string('token', 32)->index();

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

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
