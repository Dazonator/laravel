<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessagesTaskNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $template;
    public $body;
    public function __construct($template, $body)
    {
        $this->template = $template;
        $this->body = $body;
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
            'body' => $this->body,
        ]);
    }
}
