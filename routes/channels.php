<?php

use App\Models\Tasks;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('tasks.{taskId}', function ($user, $taskId) {
    $task = Tasks::find($taskId);

    return ($user->id === $task->initiator_id) || (!empty($task->performers_id) && in_array($user->id, $task->performers_id)) || $user->id === $task->creator_id;
});
