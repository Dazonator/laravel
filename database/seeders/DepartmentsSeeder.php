<?php

namespace Database\Seeders;

use App\Models\Departments;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('departments')->delete();

        $departments = [
            ['id' => 1, 'department' => 'Реклама Monro24'],
            ['id' => 2, 'department' => 'Реклама МЛ'],
            ['id' => 3, 'department' => 'Программисты'],
            ['id' => 4, 'department' => 'Менеджеры Monro24'],
            ['id' => 5, 'department' => 'Менеджеры МЛ'],
            ['id' => 6, 'department' => 'Менеджеры соц. сети'],
            ['id' => 7, 'department' => 'Колл-центр'],
            ['id' => 8, 'department' => 'Бухгалтерия'],
            ['id' => 9, 'department' => 'Водители'],
            ['id' => 10, 'department' => 'Склад основной'],
            ['id' => 11, 'department' => 'Склад рекламаций'],
            ['id' => 12, 'department' => 'Контенты'],
            ['id' => 13, 'department' => 'Роководство'],
        ];

        Departments::insert($departments);
    }
}
