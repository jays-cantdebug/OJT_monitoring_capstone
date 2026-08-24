<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeanAccountCreatedNotification extends Notification
{
    use Queueable;

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Deliberately carries no credential data - see
     * Dean\DeanAccountController::store() for why.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'approval',
            'text' => 'Welcome to NORMI — your Dean account is ready to use.',
        ];
    }
}
