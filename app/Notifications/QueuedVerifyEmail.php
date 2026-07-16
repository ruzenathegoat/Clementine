<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;

class QueuedVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Verify Your Identity - Clementine')
            ->view('emails.verify-email', [
                'url' => $verificationUrl,
                'notifiable' => $notifiable
            ]);
    }
}
