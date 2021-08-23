<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', function () {
    return view('home');
});

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/tasks', function () {
    return view('tasks');
});

Route::get('/messages', function () {
    return view('messages');
});

Route::get('/team', function () {
    return view('team');
});

Route::get('/structure', function () {
    return view('structure');
});

Route::get('/profile', function () {
    return view('profile');
});


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
