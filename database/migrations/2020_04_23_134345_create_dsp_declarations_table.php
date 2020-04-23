<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDspDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsp_declarations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('declaration_id');
            $table->mediumText('body');
            $table->timestamps();

            $table->foreign('declaration_id')
                ->references('id')
                ->on('declarations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dsp_declarations');
    }
}
