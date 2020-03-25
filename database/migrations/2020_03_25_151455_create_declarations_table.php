<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDeclarationsTable
 */
class CreateDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('code_id');

            $table->string('document_type', 16);
            $table->string('document_series', 16);
            $table->string('document_number', 32);
            $table->string('travelling_from_country_code', 2);
            $table->string('travelling_from_city', 32);
            $table->date('travelling_from_date');
            $table->date('home_country_return_date');
            $table->string('question_1_answer', 512);
            $table->string('question_2_answer', 512);
            $table->string('question_3_answer', 512);
            $table->boolean('symptom_fever');
            $table->boolean('symptom_swallow');
            $table->boolean('symptom_breathing');
            $table->boolean('symptom_cough');
            $table->string('vehicle_type', 16);
            $table->string('vehicle_registration_no', 16)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('code_id')
                ->references('id')
                ->on('codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declarations');
    }
}
