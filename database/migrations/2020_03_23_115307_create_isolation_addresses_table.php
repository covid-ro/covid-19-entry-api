<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateIsolationAddressesTable
 */
class CreateIsolationAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isolation_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('city', 64);
            $table->string('county', 64)->index();
            $table->string('city_full_address', 256);
            $table->date('city_arrival_date');
            $table->date('city_departure_date')->nullable();
            $table->timestamps();

            $table->index(['city', 'county']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isolation_addresses');
    }
}
