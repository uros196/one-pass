<?php

namespace App\Notifications;

use App\Contracts\SensitiveData\ExpirableDataContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class DataExpireSoonNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param ExpirableDataContract $model
     */
    public function __construct(protected ExpirableDataContract $model) {}

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
        // TODO: improve this notification to looks nice

        $provider = $this->model->expireNotificationDataProvider();
        $days = now()->diffInDays($this->expirationDate());

        return (new MailMessage)
            ->subject('Document expiration notification')
            ->line("You document is about to expire for $days days.")
            ->action('See the document', $provider->link());
    }

    /**
     * Get expiration date.
     *
     * @return Carbon
     */
    protected function expirationDate(): Carbon
    {
        return $this->model->dataConnection->dataExpirationDate->expires_at;
    }
}
