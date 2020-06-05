<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class SirutaCountiesSeeder
 */
class SirutaCountiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(DB::table('counties')->count())) {
            DB::table('siruta')
                ->where('TIP', '=', 40)
                ->orderBy('SIRUTA', 'ASC')
                ->each(function ($line) {
                    DB::table('counties')->insert([
                        ['id' => $line->JUD, 'name' => str_replace('JUDETUL ', '', $line->DENLOC)]
                    ]);
                });
        }
    }
}
