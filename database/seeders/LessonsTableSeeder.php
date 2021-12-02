<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++){
            $kid_id = rand(1,10);
            Lesson::create([
                'kid_id'                => $kid_id,
                'name'                  => 'スイミング',
                'schedule'              => 4,
                'pu_plan_guardian_id'   => $kid_id,
                'pu_hour'               => rand(9,16),
                'pu_minute'             => rand(0,59)
            ]);
        }
    }
}
