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
            $data = '{"RECORDS":[{"id":"1","code":"","name":"P.T.F. Nadlac II","created_at":"23/3/2020 12:32:06","updated_at":"23/3/2020 12:32:06","deleted_at":""},{"id":"2","code":"","name":"P.T.F. Cenad","created_at":"23/3/2020 12:33:08","updated_at":"23/3/2020 12:33:08","deleted_at":""},{"id":"3","code":"","name":"P.T.F. Giurgiu (rutier)","created_at":"23/3/2020 12:33:25","updated_at":"23/3/2020 12:33:25","deleted_at":""},{"id":"4","code":"","name":"P.T.F. Aeroport Cluj-Napoca","created_at":"23/3/2020 13:24:25","updated_at":"23/3/2020 13:24:25","deleted_at":""},{"id":"5","code":"","name":"P.T.F. Aeroport Otopeni","created_at":"23/3/2020 13:49:12","updated_at":"23/3/2020 13:49:12","deleted_at":""},{"id":"6","code":"","name":"P.T.F. Aeroport Timisoara","created_at":"23/3/2020 13:51:11","updated_at":"23/3/2020 13:51:11","deleted_at":""},{"id":"7","code":"","name":"P.T.F. Nadlac","created_at":"23/3/2020 13:51:12","updated_at":"23/3/2020 13:51:12","deleted_at":""},{"id":"8","code":"","name":"P.T.F. Aeroport Iasi","created_at":"23/3/2020 14:16:11","updated_at":"23/3/2020 14:16:11","deleted_at":""},{"id":"9","code":"","name":"P.T.F. Bors","created_at":"23/3/2020 14:22:12","updated_at":"23/3/2020 14:22:12","deleted_at":""},{"id":"10","code":"","name":"P.T.F. Varsand","created_at":"23/3/2020 14:40:12","updated_at":"23/3/2020 14:40:12","deleted_at":""},{"id":"11","code":"","name":"P.T.F. Ostrov","created_at":"23/3/2020 14:59:12","updated_at":"23/3/2020 14:59:12","deleted_at":""},{"id":"12","code":"","name":"P.T.F. Petea","created_at":"23/3/2020 15:11:12","updated_at":"23/3/2020 15:11:12","deleted_at":""},{"id":"13","code":"","name":"P.T.F. Vama Veche","created_at":"23/3/2020 15:11:12","updated_at":"23/3/2020 15:11:12","deleted_at":""},{"id":"14","code":"","name":"P.T.F. Aeroport Suceava","created_at":"23/3/2020 15:33:11","updated_at":"23/3/2020 15:33:11","deleted_at":""},{"id":"15","code":"","name":"P.T.F. Aeroport Craiova","created_at":"23/3/2020 16:03:12","updated_at":"23/3/2020 16:03:12","deleted_at":""},{"id":"16","code":"","name":"P.T.F. Calafat","created_at":"23/3/2020 16:39:12","updated_at":"23/3/2020 16:39:12","deleted_at":""},{"id":"17","code":"","name":"P.T.F. Siret","created_at":"23/3/2020 16:45:12","updated_at":"23/3/2020 16:45:12","deleted_at":""},{"id":"18","code":"","name":"P.T.F. Aeroport Targu Mures","created_at":"23/3/2020 16:48:12","updated_at":"23/3/2020 16:48:12","deleted_at":""},{"id":"19","code":"","name":"P.T.F. Aeroport Bacau","created_at":"23/3/2020 17:46:11","updated_at":"23/3/2020 17:46:11","deleted_at":""},{"id":"20","code":"","name":"P.T.F. Bechet","created_at":"23/3/2020 19:01:12","updated_at":"23/3/2020 19:01:12","deleted_at":""},{"id":"21","code":"","name":"P.T.F. Aeroport Sibiu","created_at":"23/3/2020 19:52:11","updated_at":"23/3/2020 19:52:11","deleted_at":""},{"id":"22","code":"","name":"P.T.F. Aeroport Constanta","created_at":"24/3/2020 03:10:12","updated_at":"24/3/2020 03:10:12","deleted_at":""},{"id":"23","code":"","name":"P.T.F. Aeroport Baneasa","created_at":"24/3/2020 03:21:12","updated_at":"24/3/2020 03:21:12","deleted_at":""},{"id":"24","code":"","name":"P.T.F. Urziceni","created_at":"24/3/2020 04:59:11","updated_at":"24/3/2020 04:59:11","deleted_at":""},{"id":"25","code":"","name":"P.T.F. Aeroport Arad","created_at":"24/3/2020 12:13:11","updated_at":"24/3/2020 12:13:11","deleted_at":""},{"id":"26","code":"","name":"P.T.F. Portile de Fier I","created_at":"25/3/2020 11:37:12","updated_at":"25/3/2020 11:37:12","deleted_at":""},{"id":"27","code":"","name":"P.T.F. Albita","created_at":"25/3/2020 14:06:12","updated_at":"25/3/2020 14:06:12","deleted_at":""},{"id":"28","code":"","name":"P.T.F. Stamora-Moravita (rutier)","created_at":"25/3/2020 15:11:11","updated_at":"25/3/2020 15:11:11","deleted_at":""},{"id":"29","code":"","name":"P.T.F. Sculeni","created_at":"26/3/2020 02:31:11","updated_at":"26/3/2020 02:31:11","deleted_at":""},{"id":"30","code":"","name":"P.T.F. Sacuieni","created_at":"26/3/2020 14:07:13","updated_at":"26/3/2020 14:07:13","deleted_at":""},{"id":"31","code":"","name":"P.T.F. Drobeta-Turnu Severin","created_at":"26/3/2020 15:08:12","updated_at":"26/3/2020 15:08:12","deleted_at":""},{"id":"32","code":"","name":"P.T.F. Stanca","created_at":"26/3/2020 19:28:11","updated_at":"26/3/2020 19:28:11","deleted_at":""},{"id":"33","code":"","name":"P.T.F. Halmeu (rutier)","created_at":"26/3/2020 19:36:12","updated_at":"26/3/2020 19:36:12","deleted_at":""},{"id":"34","code":"","name":"P.T.F. Jimbolia (rutier)","created_at":"26/3/2020 19:39:12","updated_at":"26/3/2020 19:39:12","deleted_at":""},{"id":"35","code":"","name":"P.T.F. Galati (rutier)","created_at":"26/3/2020 20:30:12","updated_at":"26/3/2020 20:30:12","deleted_at":""},{"id":"36","code":"","name":"P.T.F. Galati (feroviar)","created_at":"26/3/2020 20:46:12","updated_at":"26/3/2020 20:46:12","deleted_at":""},{"id":"37","code":"","name":"P.T.F. Galati (portuar)","created_at":"26/3/2020 23:12:11","updated_at":"26/3/2020 23:12:11","deleted_at":""},{"id":"38","code":"","name":"P.T.F. Constanta-Sud-Agigea","created_at":"27/3/2020 08:28:11","updated_at":"27/3/2020 08:28:11","deleted_at":""},{"id":"39","code":"","name":"P.T.F. Vicsani","created_at":"27/3/2020 09:24:12","updated_at":"27/3/2020 09:24:12","deleted_at":""},{"id":"40","code":"","name":"P.T.F. Constanta","created_at":"27/3/2020 09:28:12","updated_at":"27/3/2020 09:28:12","deleted_at":""},{"id":"41","code":"","name":"P.T.F. Giurgiu (portuar)","created_at":"27/3/2020 09:56:12","updated_at":"27/3/2020 09:56:12","deleted_at":""},{"id":"42","code":"","name":"P.T.F. Giurgiu (feroviar)","created_at":"27/3/2020 12:28:12","updated_at":"27/3/2020 12:28:12","deleted_at":""},{"id":"43","code":"","name":"P.T.F. Iasi","created_at":"27/3/2020 12:40:13","updated_at":"27/3/2020 12:40:13","deleted_at":""},{"id":"44","code":"","name":"P.T.F. Salonta (rutier)","created_at":"27/3/2020 13:06:12","updated_at":"27/3/2020 13:06:12","deleted_at":""},{"id":"45","code":"","name":"P.T.F. Halmeu (feroviar)","created_at":"27/3/2020 14:10:12","updated_at":"27/3/2020 14:10:12","deleted_at":""},{"id":"46","code":"","name":"P.T.F. Cernavoda","created_at":"27/3/2020 15:48:12","updated_at":"27/3/2020 15:48:12","deleted_at":""},{"id":"47","code":"","name":"P.T.F. Midia","created_at":"27/3/2020 19:34:12","updated_at":"27/3/2020 19:34:12","deleted_at":""},{"id":"48","code":"BCP1","name":"P.T.F. Nadlac II","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"49","code":"BCP2","name":"P.T.F. Cenad","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"50","code":"BCP3","name":"P.T.F. Giurgiu (rutier)","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"51","code":"BCP4","name":"P.T.F. Aeroport Cluj-Napoca","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"52","code":"BCP5","name":"P.T.F. Aeroport Otopeni","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"53","code":"BCP6","name":"P.T.F. Aeroport Timisoara","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"54","code":"BCP7","name":"P.T.F. Nadlac","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"55","code":"BCP8","name":"P.T.F. Aeroport Iasi","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"56","code":"BCP9","name":"P.T.F. Bors","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"57","code":"BCP10","name":"P.T.F. Varsand","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"58","code":"BCP11","name":"P.T.F. Ostrov","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"59","code":"BCP12","name":"P.T.F. Petea","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"60","code":"BCP13","name":"P.T.F. Vama Veche","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"61","code":"BCP14","name":"P.T.F. Aeroport Suceava","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"62","code":"BCP15","name":"P.T.F. Aeroport Craiova","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"63","code":"BCP16","name":"P.T.F. Calafat","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"64","code":"BCP17","name":"P.T.F. Siret","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"65","code":"BCP18","name":"P.T.F. Aeroport Targu Mures","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"66","code":"BCP19","name":"P.T.F. Aeroport Bacau","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"67","code":"BCP20","name":"P.T.F. Bechet","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"68","code":"BCP21","name":"P.T.F. Aeroport Sibiu","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"69","code":"BCP22","name":"P.T.F. Aeroport Constanta","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"70","code":"BCP23","name":"P.T.F. Aeroport Baneasa","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"71","code":"BCP24","name":"P.T.F. Urziceni","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"72","code":"BCP25","name":"P.T.F. Aeroport Arad","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"73","code":"BCP26","name":"P.T.F. Portile de Fier I","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"74","code":"BCP27","name":"P.T.F. Albita","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"75","code":"BCP28","name":"P.T.F. Stamora-Moravita (rutier)","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"76","code":"BCP29","name":"P.T.F. Sculeni","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"77","code":"BCP30","name":"P.T.F. Sacuieni","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"78","code":"BCP31","name":"P.T.F. Drobeta-Turnu Severin","created_at":"27/3/2020 21:31:27","updated_at":"27/3/2020 21:31:27","deleted_at":""},{"id":"79","code":"","name":"P.T.F. Sulina","created_at":"28/3/2020 09:49:13","updated_at":"28/3/2020 09:49:13","deleted_at":""},{"id":"80","code":"","name":"P.T.F. Mangalia","created_at":"28/3/2020 12:03:12","updated_at":"28/3/2020 12:03:12","deleted_at":""},{"id":"81","code":"","name":"P.T.F. Calarasi","created_at":"28/3/2020 18:23:12","updated_at":"28/3/2020 18:23:12","deleted_at":""},{"id":"82","code":"","name":"P.T.F. Stamora-Moravita (feroviar)","created_at":"28/3/2020 18:41:12","updated_at":"28/3/2020 18:41:12","deleted_at":""},{"id":"83","code":"","name":"P.T.F. Tulcea","created_at":"30/3/2020 12:00:13","updated_at":"30/3/2020 12:00:13","deleted_at":""},{"id":"84","code":"","name":"P.T.F. Jimbolia (feroviar)","created_at":"31/3/2020 13:56:11","updated_at":"31/3/2020 13:56:11","deleted_at":""},{"id":"85","code":"","name":"P.T.F. Braila","created_at":"6/4/2020 13:09:37","updated_at":"6/4/2020 13:09:37","deleted_at":""},{"id":"86","code":"","name":"P.T.F. Curtici","created_at":"7/4/2020 16:06:38","updated_at":"7/4/2020 16:06:38","deleted_at":""}]}';

            /** @var stdClass $data */
            $data = json_decode($data);

            /** @var stdClass $record */
            foreach ($data->RECORDS as $record) {
                DB::table('border_checkpoints')->insert([
                    ['name' => str_replace('P.T.F. ', '', $record->name), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ]);
            }
        }
    }
}
