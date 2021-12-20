<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{

    public function addUser(AddUserRequest $request){
        $user = User::create([
            'login' => $request->login,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'position' => $request->position,
        ]);
        if($request->img){
            $user->update([
                'img' => $request->img
            ]);
        }
        $user->roles()->sync($request->roles);
        $this->updatePermissionsFromUser($user->id);

    }
    public function updateUser(Request $request){
        $id = $request->id;
        $user = User::find($id);
        $user->update([
            'login' => $request->login,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'position' => $request->position,
        ]);
        if($request->img){
            $user->update([
                'img' => $request->img
            ]);
        }
        $user->roles()->sync($request->roles);



        $this->updatePermissionsFromUser($id);
    }

    public function removeRoleFromUser(Request $request)
    {
        $user = User::where('id', $request->user)->with(['roles', 'permissions'])->first();
        $roles = array_column(json_decode($user->roles, true), 'id');

        unset($roles[array_search($request->role, $roles)]);
        $roles = array_values($roles);
        $user->roles()->sync($roles);


        $this->updatePermissionsFromUser($user->id);
    }

    public function removePermissionFromRole(Request $request){
        $role = Role::where('id', $request->role)->with('permissions')->first();
        $permissions = array_column(json_decode($role->permissions, true), 'id');

        unset($permissions[array_search($request->permission, $permissions)]);
        $permissions = array_values($permissions);
        $role->permissions()->sync($permissions);

        $this->updatePermissionsFromAllUsers();
    }

    public function updatePermissionsFromUser($id){
        $user = User::where('id', $id)->with(['roles', 'permissions'])->first();
        $roles = array_column(json_decode($user->roles, true), 'id');

        $permissions = array();
        foreach ($roles as $role){
            $per = Role::where('id', $role)->with('permissions')->first();
            foreach ($per->permissions as $pers){
                if(!array_search($pers->id, $permissions)){
                    array_push($permissions, $pers->id);
                }
            }
        }
        $user->permissions()->sync($permissions);
    }
    public function updatePermissionsFromAllUsers(){
        $users = User::with(['roles', 'permissions'])->get();
        foreach ($users as $user){
            $roles = array_column(json_decode($user->roles, true), 'id');

            $permissions = array();
            foreach ($roles as $role){
                $per = Role::where('id', $role)->with('permissions')->first();
                foreach ($per->permissions as $pers){
                    if(!array_search($pers->id, $permissions)){
                        array_push($permissions, $pers->id);
                    }
                }
            }
            $user->permissions()->sync($permissions);
        }
    }

    public function deleteUser($id){
        $user = User::find($id);
        $user->delete();
    }


    public function uploadPhoto (Request $request){
        $file = $request->img;
        $path   = 'images/users';
        return Storage::disk('public')->putFile($path, $file);
    }

    public function getUser($id){
        return User::where('id', $id)->with('roles')->first();
    }

    public function getUsers()
    {
        return User::with('roles')->select('id', 'img', 'name', 'lastname', 'position')->get();
    }

    public function getPermissions()
    {
        return Permission::all();
    }

    public function getRoles()
    {
        return Role::with(['permissions', 'users'])->get();
    }

    public function getRoleForUpdate($id){
        return Role::where('id', $id)->with('permissions')->first();
    }

    public function createRole(Request $request){
        $role = new Role();
        $role::create([
            'name' => $request->name,
            'slug' => 'test'
        ])->permissions()->sync($request->permissions);
    }
    public function updateRole(Request $request){
        $id = $request->id;
        $role = Role::find($id);
        $role->update([
            'name' => $request->name,
        ]);
        $role->permissions()->sync($request->permissions);
        $this->updatePermissionsFromAllUsers();
    }
    public function deleteRole($id){
        $role = Role::find($id);
        $role->delete();
        $this->updatePermissionsFromAllUsers();
    }

//    public function getUserForUpdate(Request $request){
//        $user = User::find($request->id);
//    }
}
