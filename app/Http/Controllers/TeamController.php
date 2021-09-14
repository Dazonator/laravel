<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function getDepartments(){
        return Departments::with([
            'users' => function($q) {
                $q->with(['department']);
            }
        ])->get();
    }
}
