<?php

use App\Http\Controllers\AddUserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\StepsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\AddTaskController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\TasksController;
use \App\Http\Controllers\TeamController;
use \App\Http\Controllers\CalendarController;


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


Route::get('/profile/{id?}', [UserController::class, 'profile']);


Route::get('/employees', [UserController::class, 'employees']);
Route::get('/priorities', [UserController::class, 'priorities']);
Route::get('/departments', [AddUserController::class, 'getDepartments']);

Route::post('/add-task-params', [AddTaskController::class, 'getParamsAddTask']);
Route::post('/meetings-users', [UserController::class, 'getUsers']);
Route::post('/main-app-parameters', [Controller::class, 'mainAppParameters']);





Route::post('/calendar/events', [CalendarController::class, 'getEvents']);

Route::post('/calendar/event/{id}', [CalendarController::class, 'getById']);
Route::post('/calendar/create', [CalendarController::class, 'submitEvent']);

Route::post('/calendar/update/{id}', [CalendarController::class, 'updateEvent']);
Route::post('/calendar/delete/{id}', [CalendarController::class, 'deleteEvent']);


Route::get('/tasks', [TasksController::class, 'userTasks']);
Route::get('/tasks/statuses-departments', [TasksController::class, 'getStatusesAndDepartments']);

Route::get('/tasks/statuses/{id}', [TasksController::class, 'statusTasks']);
Route::get('/tasks/department/{id}', [TasksController::class, 'tasksByDepartment']);
Route::get('/tasks/parent-steps/{id}', [StepsController::class, 'getParentSteps']);
Route::post('/tasks/task/messages/send', [MessagesController::class, 'sendMessage']);
Route::post('/tasks/task/messages/{id}', [MessagesController::class, 'getChatMessages']);
Route::post('/tasks/task/create-new-step', [StepsController::class, 'createNewStep']);
Route::post('/tasks/task/update-step-title', [StepsController::class, 'updateStepTitle']);
Route::post('/tasks/task/update-task-step', [StepsController::class, 'updateTaskStep']);
Route::post('/tasks/task/delete-step', [StepsController::class, 'deleteStep']);
Route::post('/tasks/task/delete-step/{id}', [StepsController::class, 'deleteStep']);
Route::post('/tasks/task/update-step/{id}', [StepsController::class, 'updateStep']);
Route::get('/tasks/task/{id}', [TasksController::class, 'getTask']);
Route::get('/tasks/edit/{id}', [TasksController::class, 'editTask']);


Route::get('/team/departments', [TeamController::class, 'getDepartments']);
Route::get('/team/departments/{id}', [TeamController::class, 'getDepartmentUsers']);
Route::get('/team/users/{id}', [TeamController::class, 'getUserTasks']);


Route::post('/profile/change-password', [UserController::class, 'changePassword']);
Route::post('/profile/change-photo', [UserController::class, 'changePhoto']);

Route::post('/team/adduser', [UserController::class, 'addUser']);
Route::post('/team/adduser/uploadPhoto', [UserController::class, 'uploadPhoto']);


Route::post('/tasks/create', [TasksController::class, 'submitTask']);
Route::post('/tasks/update/{id}', [TasksController::class, 'updateTask']);
Route::post('/tasks/completed/{id}', [TasksController::class, 'completedTask']);
Route::post('/tasks/restore/{id}', [TasksController::class, 'restoreTask']);
Route::post('/tasks/delete/{id}', [TasksController::class, 'deleteTask']);


Route::get('/messages', [MessagesController::class, 'messagesNotifications']);
Route::get('/notifications', [MessagesController::class, 'getNotifications']);



