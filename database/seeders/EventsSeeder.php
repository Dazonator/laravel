<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Events')->insert([
            'title' => 'Event'.Str::random(10),
            'text' => 'Text event'.Str::random(30),
            'start' => '2021-09-22 13:52',
            'end' => '2021-09-22 16:52',
        ]);
    }
}
