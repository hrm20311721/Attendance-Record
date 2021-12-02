<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            HolidaysTableSeeder::class,
            GradesTableSeeder::class,
            KidsTableSeeder::class,
            GuardiansTableSeeder::class,
            LessonsTableSeeder::class,
            RecordsTableSeeder::class
        ]);
    }
}
