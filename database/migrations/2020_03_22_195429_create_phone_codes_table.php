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
            $table->string('country_code', 2)->nullable();
            $table->string('phone_number', 32);
            $table->string('formatted_phone_number', 32)->nullable();
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
        Schema::dropIfExists('phonecodes');
    }
}
