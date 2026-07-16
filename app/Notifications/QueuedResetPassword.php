<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class QueuedResetPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Password Reset Request - Clementine')
            ->view('emails.reset-password', [
                'url' => url(route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)),
                'notifiable' => $notifiable
            ]);
    }
}
