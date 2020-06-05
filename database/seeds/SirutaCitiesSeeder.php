<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class SirutaCitiesSeeder
 */
class SirutaCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(DB::table('cities')->count())) {
            DB::table('siruta')
                ->where('NIV', '=', 2)
                ->orderBy('SIRUTA', 'ASC')
                ->each(function ($line) {
                    DB::table('cities')->insert([
                        ['siruta_id' => $line->SIRUTA, 'siruta_parent_id' => $line->SIRSUP, 'name' => $line->DENLOC]
                    ]);
                });
        }
    }
}
