<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDeclarationSignaturesTable
 */
class CreateDeclarationSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declaration_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('declaration_id');
            $table->longText('image');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('declaration_signatures');
    }
}
