<?php

namespace App\Http\Controllers;


use App\Http\Requests\AddUserRequest;
use App\Models\Departments;
use App\Models\Priority;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function Sodium\add;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function profile($id=null)
    {
        if ($id){

            $isUser = false;
            $authUser = Auth::id();
            if($id & ($id == $authUser)){
                $isUser = true;
            }

            return [
                'is_user' => $isUser,
                'user' => User::where('id', $id)->with(['department'])->first()
            ];
        }
        return [
            'is_user' => true,
            'user' => User::where('id', Auth::user()->id)->with(['department'])->first()
        ];
    }

    public function changePassword(Request $request){
        $user = Auth::user();

        $userPassword = $user->password;
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;


        if (!Hash::check($oldPassword, $userPassword)) {
            throw ValidationException::withMessages(
                ['field_name' => 'Старый пароль не верный']
            );
        }

        $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);


        $user->password = Hash::make($newPassword);
        $user->save();
    }


    public function changePhoto(Request $request){
        $file = $request->file;
        $path   = 'images/users';

        $user = User::where('id', Auth::user()->id)->first();

        $user->img = Storage::disk('public')->putFile($path, $file);
        $user->save();
    }


    public function getUsers()
    {
        return User::select('id', 'img', 'name', 'lastname', 'position')->get();
    }

    public function addUserParameters (){
        return [
            'departments' => Departments::all(),
            'roles' => Role::all(),
        ];
    }


}
