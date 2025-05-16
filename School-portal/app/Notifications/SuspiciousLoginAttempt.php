<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginAttempt extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

     public function __construct()
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Security Alert: Multiple Failed Login Attempts')
        ->greeting('Hello ' . $notifiable->name)
        ->line('We noticed multiple failed login attempts to your account.')
        ->line('If this was not you, please consider changing your password immediately.')
        ->action('Reset Password', url('/password/reset'))
        ->line('Your account has been temporarily locked for 10 minutes to protect your security.');
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
