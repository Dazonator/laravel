<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user::create([
            'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPUrabYnhmhW-R0ruChIf03eExU4ETJhJYRA&usqp=CAU',
            'login' => 'admin',
            'name' => 'administrator',
            'lastname' => 'superadmin',
            'phone' => '+37529123456789',
            'position' => 'administrator',
            'department_id' => 2,
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('11111111'),
        ]);

        $this->call([
            DepartmentsSeeder::class,
            PrioritiesSeeder::class,
            StatusSeeder::class,
            PermissionsSeeder::class,
        ]);
    }
}
