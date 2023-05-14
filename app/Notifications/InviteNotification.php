<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification
{
    use Queueable;
    protected $notificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($notificationUrl)
    {
        $this->notificationUrl = $notificationUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Registrierung bei Laravel Quizible')
            ->greeting('Hallo!')
            ->line('Das ist eine Einladung zur Registrierung auf ' . config('app.name'))
            ->action('Jetzt registrieren',$this->notificationUrl)
            ->line('Wichtiger Hinweis: der Registrierungs-Link läuft in 300 Minuten ab!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
