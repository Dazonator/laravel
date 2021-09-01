<?php

namespace Database\Seeders;

use App\Models\Positions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->delete();

        $positions = [
            ['id' => 1, 'position' => 'Программист'],
            ['id' => 2, 'position' => 'Системный администратор'],
            ['id' => 3, 'position' => 'Рекламщик'],
            ['id' => 4, 'position' => 'Водитель'],
            ['id' => 5, 'position' => 'Складской рабочий'],
            ['id' => 6, 'position' => 'Менеджер'],
            ['id' => 7, 'position' => 'Звонящий'],
            ['id' => 8, 'position' => 'Бухгалтер'],
            ['id' => 9, 'position' => 'Главный Бухгалтер'],
            ['id' => 10, 'position' => 'Контент-менеджер'],
        ];

        Positions::insert($positions);
    }
}
