<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePhoneCodesTable
 */
class CreatePhoneCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('code');
            $table->string('country_prefix', 3);
            $table->string('phone_number', 32);
            $table->string('phone_identifier')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['country_prefix', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phonecodes');
    }
}
