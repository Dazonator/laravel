<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsersTaskNotifications extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $template;
    private $userName;
    private $title;
    private $body;
    private $taskId;
    private $updated;

    public function __construct($template, $userName, $title, $body, $taskId, $updated)
    {
        $this->template = $template;
        $this->userName = $userName;
        $this->title = $title;
        $this->body = $body;
        $this->taskId = $taskId;
        $this->updated = $updated;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'template' => $this->template,
            'userName' => $this->userName,
            'title' => $this->title,
            'body' => $this->body,
            'task_id' => $this->taskId,
            'updated' => $this->updated,
        ]);
    }
}
