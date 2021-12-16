<?php

use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddTaskController;
use \App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/{any}', function () {
    if (Auth::check()) {
        return view('home');
    }
    return view('auth.login');
})->where('any', '.*');

//Route::get('/{any}', [HomeController::class, 'index'])->where('any', '.*');

Auth::routes();

