<?php

namespace App\Listeners;

use App\Events\TaskUpdatedEvent;
use App\Models\Status;
use App\Models\User;
use App\Notifications\UsersTaskNotifications;
use Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class UpdatedTaskNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  taskUpdatedEvent  $event
     * @return void
     */

    public function handle(TaskUpdatedEvent $event)
    {
        $body = '';
        $title = 'Задача изменена';
        $user = Auth::user();
        $userName = $user->name . ' ' . $user->lastname;
        $body .= $userName;

        $updated = $event->updated;
        $task = $event->task;
        $template = $event->template;

        //для кого транслировать
        $messageFor = [];
        if(!empty($task['performers_id'])){
            for($i = 0; $i < count($task['performers_id']); ++$i) {
                if($user['id'] !== $task['performers_id'][$i]){
                    array_push($messageFor, $task['performers_id'][$i]);
                }
            }
        }
        if (!empty($task['initiator_id']) && !in_array($task['initiator_id'], $messageFor) && $user['id'] !== $task['initiator_id']){
            array_push($messageFor, $task['initiator_id']);
        }
        $users = User::whereIn('id', $messageFor)->get();
        //end. для кого транслировать


        if(!empty($updated['status_id'])){
            $status = Status::where('id', $updated['status_id'])->first();
            $body .= ' изменил(a) статус задачи "' . $task['title'] . '" на "' . $status['status'] . '"';
        } else {
            $body .= ' изменил(a) задачу ' . $task['title'];
        }

        $taskId = $task['id'];

        Notification::send($users, new UsersTaskNotifications($template, $userName, $title, $body, $taskId, $updated));
    }
}
