<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use Illuminate\Http\Request;

class AddUserController extends Controller
{

    public function getDepartments(){
        return Departments::all();
    }
}
