<?php

use App\BorderCheckpoint;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class BorderCheckpointTableSeeder
 */
class BorderCheckpointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(BorderCheckpoint::all()->count())) {
            DB::table('border_checkpoints')->insert([
                ['name' => 'Albița', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Arad', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Aurel Vlaicu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Bacău', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Bechet', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Borș', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Brăila', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Calafat', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Călărași', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Capul Midia', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Carei', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Câmpulung la Tisa', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Cenad', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Cernavodă', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Cluj', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Constanța', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Constanța Sud', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Corabia', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Craiova', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Curtici', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Drobeta Turnu Severin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Episcopia Bihor', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Fălciu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Galați', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Giurgiu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Henri Coandă', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Iași', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Jimbolia', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Lipnița', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Mangalia', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Mihail Kogălniceanu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Moldova Veche', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Naidăș', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Nădlac', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Nicolina', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Oancea', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Oltenița', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Oradea', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Orșova', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Ostrov', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Petea', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Porțile de Fier I', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Porțile de Fier II', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Salonta', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Satu Mare', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Săcuieni', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Sculeni', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Sibiu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Sighetu Marmației', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Stamora-Moravița', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Stânca', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Suceava', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Sulina', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Transilvania, Târgu Mureș', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Aeroportul Internațional Traian Vuia', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Tulcea', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Turnu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Turnu Măgurele', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Urziceni', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Valea lui Mihai', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Vama Veche', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Vărșand', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Vicșani', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Vladimirescu', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Zimnicea', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
        }
    }
}
