<?php

namespace Database\Seeders;

use App\Models\Tourism;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismTemporarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        $storage = Storage::disk('dummy')->path('output.csv');
        $handlee = fopen($storage, "r");
        for ($i = 0; $roww = fgetcsv($handlee); ++$i) {
            $arr[] = [
                'user_id' => $roww[1],
                'place_id' => $roww[2],
                'place_rating' => $roww[3],
                'place_name' => $roww[4],
                'category' => $roww[5],
                'price' => $roww[6],
                'rating' => $roww[7],
                'time_minutes' => $roww[8],
                'pengguna' => $roww[9],
                'tempat' => $roww[10]
            ];
            if (count($arr) > 100) {
                DB::table('tourism_temporaries')->insert($arr);
                echo json_encode($arr[0]);
                $arr = [];
            }
        }
        fclose($handlee);
    }
}
