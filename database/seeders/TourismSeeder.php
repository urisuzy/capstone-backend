<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = [];
        $arr = [];
        $storage = Storage::disk('dummy')->path('tourism_with_id.csv');
        $handle = fopen($storage, "r");
        for ($i = 0; $row = fgetcsv($handle); ++$i) {
            $get = Category::where('name', $row[3])->first();
            if ($get) {
                $category[$get->name] = $get->id;
            } else {
                $c = Category::create(['name' => $row[3]]);
                $category[$c->name] = $c->id;
            }
        }
        fclose($handle);

        $handlee = fopen($storage, "r");
        for ($i = 0; $roww = fgetcsv($handlee); ++$i) {

            $arr[] = [
                'id' => $roww[0],
                'name' => $roww[1],
                'description' => $roww[2],
                'category_id' => $category[$roww[3]],
                'user_id' => rand(1,300),
                'city' => $roww[4],
                'price' => $roww[5],
                'rating' => $roww[6],
                'time_minutes' => empty($roww[7]) ? 0 : $roww[7],
                'coordinate' => $roww[8],
                'thumbnail' => null,
            ];

            if (count($arr) > 100) {
                DB::table('tourisms')->insert($arr);
                $arr = [];
            }
        }
        fclose($handlee);
    }
}
