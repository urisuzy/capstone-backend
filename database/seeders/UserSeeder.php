<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $arr = [];
        $storage = Storage::disk('dummy')->path('user.csv');
        $handlee = fopen($storage, "r");
        for ($i = 0; $i <= 300; $i++) {
            $arr = [
                'username' => $faker->name(),
                'email' => $faker->email(),
                'password' => bcrypt($faker->password(8)),
                'role' => 'member',
                'profile' => null,
            ];
            DB::table('users')->insert($arr);
        }
        fclose($handlee);
    }
}
