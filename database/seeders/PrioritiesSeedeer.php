<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritiesSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('priorities')->delete();

        $priorities = [
            ['id' => 1, 'priority' => 'низкий'],
            ['id' => 2, 'priority' => 'средний'],
            ['id' => 3, 'priority' => 'высокий'],
            ['id' => 4, 'priority' => 'наивысший'],
        ];

        Priority::insert($priorities);
    }
}
