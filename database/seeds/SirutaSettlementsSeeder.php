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
                ->where('NIV', '=', 3)
                ->orderBy('SIRUTA', 'ASC')
                ->each(function ($line) {
                    DB::table('settlements')->insert([
                        ['siruta_id' => $line->SIRUTA, 'siruta_parent_id' => $line->SIRSUP, 'name' => $line->DENLOC]
                    ]);
                });
        }
    }
}
