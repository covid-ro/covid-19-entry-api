<?php

use App\City;
use App\Settlement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateSiruta
 */
class UpdateSiruta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Update `siruta` entries
         */
        Schema::disableForeignKeyConstraints();
        DB::table('siruta')->truncate();

        Schema::table('siruta', function (Blueprint $table) {
            $table->unsignedBigInteger('ULT')->nullable()->change();
        });

        Artisan::call('db:seed', ['--class' => 'SirutaImportSeeder', '--force' => '']);

        /**
         * Add missing city lines
         */
        DB::table('siruta')
            ->where('NIV', '=', 2)
            ->orderBy('SIRUTA', 'ASC')
            ->each(function ($line) {
                $existingCity = City::where('siruta_id', '=', $line->SIRUTA)->count();

                if (empty($existingCity)) {
                    DB::table('cities')->insert([
                        ['siruta_id' => $line->SIRUTA, 'siruta_parent_id' => $line->SIRSUP, 'county_id' => $line->JUD, 'name' => $line->DENLOC]
                    ]);
                }
            });

        /**
         * Add missing settlement lines
         */
        DB::table('siruta')
            ->where('NIV', '=', 3)
            ->orderBy('SIRUTA', 'ASC')
            ->each(function ($line) {
                $existingSettlement = Settlement::where('siruta_id', '=', $line->SIRUTA)->count();

                if (empty($existingSettlement)) {
                    DB::table('settlements')->insert([
                        ['siruta_id' => $line->SIRUTA, 'siruta_parent_id' => $line->SIRSUP, 'county_id' => $line->JUD, 'name' => $line->DENLOC]
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // There's no turning back from this
    }
}
