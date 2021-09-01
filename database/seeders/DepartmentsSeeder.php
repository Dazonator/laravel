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
            ['id' => 1, 'department' => 'Программисты'],
            ['id' => 2, 'department' => 'Реклама'],
            ['id' => 3, 'department' => 'Водители'],
            ['id' => 4, 'department' => 'Склад'],
            ['id' => 5, 'department' => 'Менеджеры'],
            ['id' => 6, 'department' => 'Колл-центр'],
            ['id' => 7, 'department' => 'Бухгалтерия'],
            ['id' => 8, 'department' => 'Контенты'],
            ['id' => 9, 'department' => 'Склад'],
        ];

        Departments::insert($departments);
    }
}
