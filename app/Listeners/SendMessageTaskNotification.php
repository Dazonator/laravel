<?php

namespace App\Listeners;

use App\Events\CreatedMessageTaskEvent;
use App\Models\Tasks;
use App\Models\User;
use App\Notifications\MessagesTaskNotifications;
use App\Notifications\UsersTaskNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendMessageTaskNotification
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
     * @param  CreatedMessageTaskEvent  $event
     * @return void
     */
    public function handle(CreatedMessageTaskEvent $event)
    {
        $template = 'new-task-message';
        $message = $event->message;
        $user = $event->user;
        $body = '';
        $body .= $user['name'] . ' ' . $user['lastname'];

        $task = Tasks::where('id', $message['task_id'])->first();
        $body .= ' оставил коментарий в задаче "' . $task['title'] . '".';
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

        Notification::send($users, new MessagesTaskNotifications($template, $body));
    }
}
