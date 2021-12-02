<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guardian;

class GuardiansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++){
            Guardian::create([
                'kid_id'            => $i,
                'relation'          => '母',
                'name'              => 'test_guardian'.$i,
                'created_at'        => now(),
                'updated_at'        => now()
            ]);
        }
    }
}
