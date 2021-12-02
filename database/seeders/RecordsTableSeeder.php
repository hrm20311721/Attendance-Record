<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Record;

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++){
            Record::create([
                'kid_id'                => $i,
                'do_guardian_id'        => $i,
                'do_time'               => now(),
                'pu_plan_guardian_id'   => $i,
                'pu_plan_hour'          => rand(9,18),
                'pu_plan_minute'        => rand(0,59),
                'pu_guardian_id'        => $i,
                'pu_time'               => date('Y-m-d H:i',strtotime("now + 6 hours")),
                'created_at'            => now(),
                'updated_at'            => strtotime("now + 6 hours")
            ]);
        }
    }
}
