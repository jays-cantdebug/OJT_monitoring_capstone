<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentAccountCreatedNotification extends Notification
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
     * Deliberately carries no credential data — the one-time temporary
     * password stays confined to the one-shot response rendered directly
     * in Dean\StudentAccountController::store, never persisted here.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'approval',
            'text' => 'Welcome to NORMI — your account is ready to use.',
        ];
    }
}
