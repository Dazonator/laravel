<?php

use App\Http\Controllers\AddUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\AddTaskController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\TasksController;
use \App\Http\Controllers\TeamController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::group(['middleware' => 'auth'], function () {
//
Route::get('/profile', [UserController::class, 'index']);
Route::get('/employees', [UserController::class, 'employees']);
Route::get('/priorities', [UserController::class, 'priorities']);
Route::get('/departments', [AddUserController::class, 'getDepartments']);


Route::get('/tasks', [TasksController::class, 'userTasks']);
Route::get('/tasks/{id}', [TasksController::class, 'getTask']);
Route::get('/tasks/edit/{id}', [TasksController::class, 'editTask']);

Route::get('/team', [TeamController::class, 'getDepartments']);


Route::post('/team/adduser', [UserController::class, 'addUser']);
Route::post('/team/adduser/uploadPhoto', [UserController::class, 'uploadPhoto']);

Route::post('/tasks/create', [TasksController::class, 'submitTask']);
Route::post('/tasks/update/{id}', [TasksController::class, 'updateTask']);
Route::post('/tasks/completed/{id}', [TasksController::class, 'completedTask']);


//Route::post('/add-task', [TasksController::class, 'submitTask']);
//Route::post('/add-subtask', [TasksController::class, 'submitSubtask']);



//Route::post('/add-task', [AddTaskController::class, 'submit']);




//});

//Route::apiResources([
//    'tasks' => AddTaskController::class,
//]);

//Route::get('/profile', 'UserController@index');
