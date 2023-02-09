<?php

use App\Http\Controllers\AddUserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StepsController;
use App\Http\Controllers\StructureController;
use App\Models\NotificationsUser;
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

Route::post('/ckeditor/upload', [UserController::class, 'editorUpload']);

Route::post('/profile/change-password', [UserController::class, 'changePassword']);
Route::post('/profile/change-photo', [UserController::class, 'changePhoto']);
Route::post('/profile/{id?}', [UserController::class, 'profile']);

Route::post('/employees', [UserController::class, 'employees']);
Route::post('/priorities', [UserController::class, 'priorities']);
Route::post('/departments', [AddUserController::class, 'getDepartments']);
Route::post('/add-user-parameters', [UserController::class, 'addUserParameters']);
Route::post('/permissions', [UserController::class, 'getPermissions']);


Route::post('/add-task-params', [AddTaskController::class, 'getParamsAddTask']);
Route::post('/meetings-users', [UserController::class, 'getUsers']);
Route::post('/main-app-parameters', [Controller::class, 'mainAppParameters']);


Route::post('/calendar/events', [CalendarController::class, 'getEvents']);
Route::post('/calendar/event/{id}', [CalendarController::class, 'getById']);
Route::post('/calendar/create', [CalendarController::class, 'submitEvent']);
Route::post('/calendar/update/{id}', [CalendarController::class, 'updateEvent']);
Route::post('/calendar/delete/{id}', [CalendarController::class, 'deleteEvent']);


Route::post('/calendar/meetings', [MeetingsController::class, 'getMeetingsCalendar']);
Route::post('/calendar/meetings/max-number/{id}', [MeetingsController::class, 'getMaxNumber']);
Route::post('/calendar/meetings/create', [MeetingsController::class, 'submitMeeting']);
Route::post('/calendar/meetings/update/{id}', [MeetingsController::class, 'updateMeeting']);
Route::post('/calendar/meetings/delete/{id}', [MeetingsController::class, 'deleteMeeting']);
Route::post('/calendar/meetings/{id}', [MeetingsController::class, 'getById']);


Route::post('/meetings', [MeetingsController::class, 'getMeetings']);
Route::post('/meetings/completed/{id}', [MeetingsController::class, 'completedMeeting']);
Route::post('/meetings/get-by-id-for-update/{id}', [MeetingsController::class, 'getByIdForUpdate']);
Route::post('/meetings/{id}', [MeetingsController::class, 'getById']);


Route::post('/tasks', [TasksController::class, 'userTasks']);
Route::post('/tasks/statuses-departments', [TasksController::class, 'getStatusesAndDepartments']);
Route::post('/tasks/statuses/{id}', [TasksController::class, 'statusTasks']);
Route::post('/tasks/department/{id}', [TasksController::class, 'tasksByDepartment']);
Route::post('/tasks/structure/{id}', [StructureController::class, 'tasksByStructure']);
Route::post('/tasks/tests', [TasksController::class, 'tasksTests']);
Route::post('/tasks/initiator', [TasksController::class, 'tasksInitiator']);
Route::post('/tasks/task/completeTest/{id}', [TasksController::class, 'completeTest']);
Route::post('/tasks/task/sendForTest', [TasksController::class, 'sendForTest']);


Route::post('/tasks/create', [TasksController::class, 'submitTask']);
Route::post('/tasks/create-department-task', [TasksController::class, 'submitTaskDepartment']);
Route::post('/tasks/update', [TasksController::class, 'updateTask']);
Route::post('/tasks/distribution-department', [TasksController::class, 'distributionDepartment']);
Route::post('/tasks/completed/{id}', [TasksController::class, 'completedTask']);
Route::post('/tasks/start/{id}', [TasksController::class, 'startTask']);
Route::post('/tasks/pause/{id}', [TasksController::class, 'pauseTask']);
Route::post('/tasks/delete/{id}', [TasksController::class, 'deleteTask']);

Route::post('/tasks/uploadFiles', [TasksController::class, 'uploadFiles']);
Route::post('/tasks/downloadFile/{id}', [TasksController::class, 'downloadFile']);

Route::post('/tasks/parent-steps/{id}', [StepsController::class, 'getParentSteps']);
Route::post('/tasks/task/messages/send', [MessagesController::class, 'sendMessage']);
Route::post('/tasks/task/messages/{id}', [MessagesController::class, 'getChatMessages']);
Route::post('/tasks/task/create-new-step', [StepsController::class, 'createNewStep']);
Route::post('/tasks/task/update-step-title', [StepsController::class, 'updateStepTitle']);
Route::post('/tasks/task/update-task-step', [StepsController::class, 'updateTaskStep']);
Route::post('/tasks/task/delete-step', [StepsController::class, 'deleteStep']);
Route::post('/tasks/task/delete-step/{id}', [StepsController::class, 'deleteStep']);
Route::post('/tasks/task/delete-file/{id}', [TasksController::class, 'deleteFile']);
Route::post('/tasks/task/update-step/{id}', [StepsController::class, 'updateStep']);
Route::post('/tasks/task/{id}', [TasksController::class, 'getTask']);
Route::post('/tasks/edit/{id}', [TasksController::class, 'editTask']);




Route::post('/team/departments', [TeamController::class, 'getDepartments']);
Route::post('/team/departments/{id}', [TeamController::class, 'getDepartmentUsers']);
Route::post('/team/users/{id}', [TeamController::class, 'getUserTasks']);




Route::post('/messages', [MessagesController::class, 'messagesNotifications']);
//Route::post('/notifications', [MessagesController::class, 'getNotifications']);
Route::post('/notifications-count', [NotificationsController::class, 'getNotificationsCount']);




Route::post('/structure/getStructure', [StructureController::class, 'getStructure']);
Route::post('/structure/create', [StructureController::class, 'createCategory']);
Route::post('/structure/update', [StructureController::class, 'updateCategory']);
Route::post('/structure/delete/{id}', [StructureController::class, 'deleteCategory']);

Route::post('/settings/getUsers', [SettingsController::class, 'getUsers']);
Route::post('/settings/users/getUser/{id}', [SettingsController::class, 'getUser']);
Route::post('/settings/users/addUser', [SettingsController::class, 'addUser']);
Route::post('/settings/users/addUser/uploadPhoto', [SettingsController::class, 'uploadPhoto']);
Route::post('/settings/users/update', [SettingsController::class, 'updateUser']);
Route::post('/settings/users/delete/{id}', [SettingsController::class, 'deleteUser']);

Route::post('/settings/getRoles', [SettingsController::class, 'getRoles']);
Route::post('/settings/getRoleForUpdate/{id}', [SettingsController::class, 'getRoleForUpdate']);
Route::post('/settings/getPermissions', [SettingsController::class, 'getPermissions']);

Route::post('/settings/role/create', [SettingsController::class, 'createRole']);
Route::post('/settings/role/update', [SettingsController::class, 'updateRole']);
Route::post('/settings/role/delete/{id}', [SettingsController::class, 'deleteRole']);

Route::post('/settings/removeRoleFromUser', [SettingsController::class, 'removeRoleFromUser']);
Route::post('/settings/removePermissionFromRole', [SettingsController::class, 'removePermissionFromRole']);


Route::post('/notifications/getAllNotifications', [NotificationsController::class, 'getAllNotifications']);
Route::post('/notifications/getById/{id}', [NotificationsController::class, 'getById']);
Route::post('/notifications/create', [NotificationsController::class, 'createNotification']);
Route::post('/notifications/updateNotification', [NotificationsController::class, 'updateNotification']);
Route::post('/notifications/deleteNotification/{id}', [NotificationsController::class, 'deleteNotification']);

Route::post('/documentation/getAllDocumentations', [DocumentationController::class, 'getAllDocumentations']);
Route::post('/documentation/getById/{id}', [DocumentationController::class, 'getByIdDocumentation']);
Route::post('/documentation/create', [DocumentationController::class, 'createDocumentation']);
Route::post('/documentation/update', [DocumentationController::class, 'updateDocumentation']);
Route::post('/documentation/delete/{id}', [DocumentationController::class, 'deleteDocumentation']);

