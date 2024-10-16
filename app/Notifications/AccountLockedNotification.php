<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class AccountLockedNotification extends Notification
{
    use Queueable;

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
     *
     * @param User $user
     * @return MailMessage
     */
    public function toMail(User $user): MailMessage
    {
        $url = $this->unlockingUrl($user);

        return (new MailMessage)
            ->subject(__('Your account has been locked!'))
            ->line(__('Your account has been locked due to too many failed password confirmation attempts.'))
            ->action('Unlock account', $url);
    }

    /**
     * Make the URL for unlocking account.
     *
     * @param User $user
     * @return string
     */
    protected function unlockingUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            'unlock-account.confirm',
            Carbon::now()->addDays(config('auth.unlock_link_expire')),
            [
                // encrypt the email so we can decrypt it while validating a user in 'unlocking' process
                'hash' => encrypt($user->email)
            ]
        );
    }

}
