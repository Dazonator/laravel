<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
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
    }
}
