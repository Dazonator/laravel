<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->delete();

        $statuses = [
            ['id' => 1, 'status' => 'Планы'],
            ['id' => 2, 'status' => 'В работе'],
            ['id' => 3, 'status' => 'Выполнено'],
        ];

        Status::insert($statuses);
    }
}
