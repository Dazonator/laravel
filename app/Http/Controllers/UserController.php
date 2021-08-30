<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Sodium\add;


class UserController extends Controller
{

    public function index()
    {
        return User::where('id', Auth::user()->id)->first();

    }


}
