<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;

class AdminVerifyEmail extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Multi Verify Email Address'))
            ->line(Lang::getFromJson('Please click the button below to verify your email address.'))
            ->action(
                Lang::getFromJson('Verify Email Address'),
                $this->verificationUrl($notifiable)
            )
            ->line(Lang::getFromJson('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'admin.verification.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );
    }
}
