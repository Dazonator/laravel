<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        $permissions = [
            [
                'name' => 'Создавать собрания',
                'slug' => 'create-meeting'
            ],
            [
                'name' => 'Редактировать собрания',
                'slug' => 'update-meeting'
            ],
            [
                'name' => 'Удалять собрания',
                'slug' => 'delete-meeting'
            ],
            [
                'name' => 'Звершать собрания',
                'slug' => 'completed-meeting'
            ],
            [
                'name' => 'Просматривать страницу собраний',
                'slug' => 'view-meeting-page'
            ],
            [
                'name' => 'Распределение заданий на собраниях !!!!!!!!!!!!!!!!!',
                'slug' => 'distributed-tasks'
            ],
            [
                'name' => 'Создание задач !!!!!!!!!!!!!!!',
                'slug' => 'create-tasks'
            ],
            [
                'name' => 'Содание задач для отдела',
                'slug' => 'create-tasks-for-department'
            ],
            [
                'name' => 'Содание задач для любого отдела',
                'slug' => 'create-tasks-for-all-department'
            ],
            [
                'name' => 'Обзор заданий других пользователей',
                'slug' => 'view-tasks-other-users'
            ],
            [
                'name' => 'Просматривать страницу структуры',
                'slug' => 'view-structure-page'
            ],
            [
                'name' => 'Редактирование структуры',
                'slug' => 'update-structure'
            ],
            [
                'name' => 'Настройки',
                'slug' => 'settings'
            ],
            [
                'name' => 'Создавать и редактировать уведомления',
                'slug' => 'notifications'
            ],
        ];

        Permission::insert($permissions);
    }
}
