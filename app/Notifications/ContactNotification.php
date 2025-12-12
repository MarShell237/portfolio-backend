<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $object,
        public string $message,
        public ?string $fileUrl = null,
        public ?string $filePath = null,
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Nouveau message : ' . $this->object)
            ->greeting('Bonjour,')
            ->line('Vous avez reçu un message de : ' . $this->name)
            ->line('Email : ' . $this->email)
            ->line('Objet : ' . $this->object)
            ->line('Message :')
            ->line($this->message);

        if ($this->filePath) {
            $mail->attach(storage_path('app/public/' . $this->filePath));
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'object' => $this->object,
            'message' => $this->message,
            'file' => $this->fileUrl,
        ];
    }
}
