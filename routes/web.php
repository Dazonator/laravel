<?php

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




//Route::get('/tasks', [AddTaskController::class, 'index']);
//Route::get('/add-tasks-get', [AddTaskController::class, 'getAddTasks']);

//Route::group(['prefix' => 'tasks'], function () {
//    Route::post('add', 'BookController@add');
//    Route::get('edit/{id}', 'BookController@edit');
//    Route::post('update/{id}', 'BookController@update');
//    Route::delete('delete/{id}', 'BookController@delete');
//});



//Route::post('/add-task', [AddTaskController::class, 'submit']);




Auth::routes();

//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
