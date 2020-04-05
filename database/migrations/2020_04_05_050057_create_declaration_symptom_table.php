<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDeclarationSymptomTable
 */
class CreateDeclarationSymptomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declaration_symptom', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('declaration_id');
            $table->unsignedBigInteger('symptom_id');

            $table->foreign('declaration_id')
                ->references('id')
                ->on('declarations');

            $table->foreign('symptom_id')
                ->references('id')
                ->on('symptoms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declaration_symptom');
    }
}
