<?php

namespace App\Listeners;

use App\Events\MeetingsEvent;
use App\Models\Departments;
use App\Models\Meetings;
use App\Models\User;
use App\Notifications\UsersMeetingNotifications;
use Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class MeetingsNotification
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
     * @param  MeetingsEvent  $event
     * @return void
     */
    public function handle(MeetingsEvent $event)
    {
        $body = '';
        $title = 'Собрание';
        $user = Auth::user();
        $userName = $user->name . ' ' . $user->lastname;
        $body .= $userName;

        $updated = $event->updated;
        $meeting = $event->meeting;
        $template = $event->template;

        //для кого транслировать
        $messageFor = [];
        if(!empty($meeting['additional_staff'])){ //дополнительные сотрудники
            for($i = 0; $i < count($meeting['additional_staff']); ++$i) {
                if($user['id'] != $meeting['additional_staff'][$i]){
                    array_push($messageFor, $meeting['additional_staff'][$i]);
                }
            }
        }
        if (!empty($meeting['initiator_id']) && !in_array($meeting['initiator_id'], $messageFor) && $user['id'] != $meeting['initiator_id']){
            array_push($messageFor, $meeting['initiator_id']);
        }
        $users = User::whereIn('id', $messageFor)->orWhere('department_id', $meeting['department_id'])->get();
//        dd($users);
        //end. для кого транслировать

        $department = Departments::where('id', $meeting['department_id'])->first();
        if(!empty($updated)){
            $body .= ' изменил(a) собрание отдела "' . $department->department . '"';
        } else {
            $body .= ' создал(a) собрание отдела "' . $department->department . '"';
        }
        $meetingId = $meeting['id'];

        Notification::send($users, new UsersMeetingNotifications($template, $userName, $title, $body, $meetingId, $updated));
    }
}
