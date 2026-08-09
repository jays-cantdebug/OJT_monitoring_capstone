<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class MissedReportNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly User $student, private readonly Carbon $date)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'alert',
            'text' => "{$this->student->name} did not submit a report for {$this->date->format('M j, Y')}.",
        ];
    }
}
