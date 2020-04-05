<?php

use App\Symptom;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class SymptomTableSeeder
 */
class SymptomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(Symptom::all()->count())) {
            DB::table('symptoms')->insert([
                ['name' => 'fever', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'swallow', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'breath', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'cough', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
        }
    }
}
