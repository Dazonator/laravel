<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsersMeetingNotifications extends Notification
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
    private $meetingId;
    private $updated;

    public function __construct($template, $userName, $title, $body, $meetingId, $updated)
    {
        $this->template = $template;
        $this->userName = $userName;
        $this->title = $title;
        $this->body = $body;
        $this->meetingId = $meetingId;
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
            'meeting_id' => $this->meetingId,
            'updated' => $this->updated,
        ]);
    }

    // ($template, $userName, $title, $body, $meetingId, $updated)
}
