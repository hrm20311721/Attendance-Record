<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kid;

class KidsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++){
            Kid::create([
                'name'          => 'test_kid'.$i,
                'grade_id'      => rand(1,5),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }
    }
}
