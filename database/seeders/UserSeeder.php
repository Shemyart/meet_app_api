<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 100; $i++){
            $user = DB::table('users')->insertGetId([
                'phone' => Str::random(11),
                'name' => Str::random(11),
                'city' => 'Ижевск',
                'description' => 'Люблю поезда',
                'gender' => 'М',
                'date_of_birth' => date('Y-m-d H:i:s'),
                'age' => rand(16, 40),
                'status' => 1,
                'verification' => 0,
                'subscribe_to_news' => 1
            ]);

        }

    }
}
