<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('code');
            $table->string('email', 255);
            $table->string('phone_identifier');
            $table->string('notes')->nullable();
            $table->string('status', 16)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['phone_identifier', 'code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_codes');
    }
}
