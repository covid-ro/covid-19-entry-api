<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BorderCheckpointTableSeeder::class);
        $this->call(SymptomTableSeeder::class);
        $this->call(SirutaImportSeeder::class);
        $this->call(SirutaCountiesSeeder::class);
        $this->call(SirutaCitiesSeeder::class);
        $this->call(SirutaSettlementsSeeder::class);
    }
}
