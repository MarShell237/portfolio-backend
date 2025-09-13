<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCustomResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = config('app.frontend_url') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
                ->subject(__('reset-password.reset_password_subject'))
                ->greeting(__('reset-password.greeting', ['name' => $notifiable->name]))
                ->line(__('reset-password.line_1'))
                ->action(__('reset-password.button'), $url)
                ->line(__('reset-password.line_2'))
                ->salutation(__('reset-password.footer', ['app_name' => config('app.name')]));
    }
}
