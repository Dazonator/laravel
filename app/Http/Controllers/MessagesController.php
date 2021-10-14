<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Tasks;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function sendMessage(Request $request){
        $userId = Auth::user()->id;

        $task = Tasks::where('id', $request->task_id)->first();

        $messageFor = [];

        if($task->performers_id){
            for($i = 0; $i < count($task->performers_id); ++$i) {
                if($task->performers_id[$i] == $userId){
                    continue;
                }
                array_push($messageFor, $task->performers_id[$i]);
            }
        }

        if ($task->initiator_id && !in_array($task->initiator_id, $messageFor) && $userId !== $task->initiator_id){
            array_push($messageFor, $task->initiator_id);
        }

        Messages::create([
            'user_id' => $userId,
            'message' => $request->message,
            'task_id' => $request->task_id,
            'message_for' => $messageFor,
        ]);
    }
    public function getChatMessages($id){
        return Messages::where('task_id', $id)->with(['user' => function($query){
            $query->select('id','img', 'name');
        }])->get();
    }
    public function messagesNotifications(){
        return Tasks::with(['taskMessages' => function ($q){
            $q->whereJsonContains('message_for', Auth::user()->id);
        }])->whereHas('taskMessages', function ($q){
            $q->whereJsonContains('message_for', Auth::user()->id);
        })->get();

//        return Tasks::with(['tasksNotifications' => function ($query) {
//            $query->whereHas('message_for', Auth::user()->id);
//        }])->get();
    }

    public function getNotifications() {
        return Messages::whereJsonContains('message_for', Auth::user()->id)->count();
    }
}
