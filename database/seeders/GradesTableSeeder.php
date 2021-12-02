<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++){
            Grade::create([
                'name'          => 'test_grade'.$i,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }
    }
}
