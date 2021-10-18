<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\MessagesUser;
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
//                if($task->performers_id[$i] == $userId){
//                    continue;
//                }
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
        ])->messagesFor()->sync($messageFor);
    }

    public function getChatMessages($id){
        $messages = Messages::where('task_id', $id)->get();
        foreach ($messages as $message) {
            MessagesUser::where('user_id', Auth::user()->id)->where('messages_id', $message->id)->delete();
        }

        return Messages::where('task_id', $id)->with(['user' => function($query){
            $query->select('id','img', 'name');
        }])->get();
    }

    public function messagesNotifications(){
        return Tasks::withCount(['taskNotifications' => function($q){
            $q->where('messages_user.user_id', Auth::user()->id);
        }])->whereHas('taskNotifications', function ($query) {
            $query->where('messages_user.user_id', Auth::user()->id);
        }, '>', 0)->get();
    }

//    public function getNotifications() {
//        return MessagesUser::where('user_id', Auth::user()->id)->count();
//    }

}
