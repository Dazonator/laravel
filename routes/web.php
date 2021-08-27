<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddTaskController;


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

//Route::get('index', 'HomeController@index');

//Route::get('/{any}', function () {
//    if (Auth::check()) {
//        return view('home');
//    }
//    return view('auth.login');
//})->where('any', '.*');

// Route::get('{any}', function () {
//     return view('home');
// })->where('any', '.*');



Route::get('/tasks', [AddTaskController::class, 'index']);
Route::get('/add-tasks-get', [AddTaskController::class, 'getAddTasks']);


//Route::group(['prefix' => 'tasks'], function () {
//    Route::post('add', 'BookController@add');
//    Route::get('edit/{id}', 'BookController@edit');
//    Route::post('update/{id}', 'BookController@update');
//    Route::delete('delete/{id}', 'BookController@delete');
//});



// Route::post('/add-task', [AddTaskController::class, 'submit']);






//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
