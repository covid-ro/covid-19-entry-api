<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class SirutaSettlementsSeeder
 */
class SirutaSettlementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(DB::table('settlements')->count())) {
            DB::table('siruta')
                ->whereIn('TIP', [9, 11, 10, 7, 8, 2, 3, 6])
                ->orderBy('SIRUTA', 'ASC')
                ->each(function ($line) {
                    DB::table('settlements')->insert([
                        ['siruta_id' => $line->SIRUTA, 'siruta_parent_id' => $line->SIRSUP, 'name' => $line->DENLOC]
                    ]);
                });
        }
    }
}
