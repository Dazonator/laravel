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
//        $developer = Role::where('slug','web-developer')->first();
//        $createTasks = Permission::where('slug','create-tasks')->first();

//        $user1 = new User();
//        $user1->img = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPUrabYnhmhW-R0ruChIf03eExU4ETJhJYRA&usqp=CAU';
//        $user1->login = 'admin';
//        $user1->name = 'administrator';
//        $user1->lastname = 'superadmin';
//        $user1->phone = '+37529123456789';
//        $user1->position = 'administrator';
//        $user1->department_id = 1;
//        $user1->email = Str::random(10).'@gmail.com';
//        $user1->password = Hash::make('11111111');
//        $user1->save();
//        $user1->roles()->attach($developer);
//        $user1->permissions()->attach($createTasks);



//        DB::table('users')->truncate();
//
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
