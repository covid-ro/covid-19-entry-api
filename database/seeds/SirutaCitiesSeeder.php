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
                ->whereIn('TIP', [1, 4, 2, 5, 3, 9])
                ->orderBy('SIRUTA', 'ASC')
                ->each(function ($line) {
                    DB::table('cities')->insert([
                        ['siruta_id' => $line->SIRUTA, 'name' => $line->DENLOC]
                    ]);
                });
        }
    }
}
