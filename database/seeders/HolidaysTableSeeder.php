<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = strtotime('2021-10-1 00:00:00');
        $end = strtotime('2021-10-31 00:00:00');

        for ($i = 1; $i <= 5; $i++){
            $holiday = rand($start,$end);
            $holiday = date('Y-m-d H:i',$holiday);
            Holiday::create([
                'holiday'       => $holiday,
                'name'          => 'test_holiday'.$i,
            ]);
        }
    }
}
