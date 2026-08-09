<?php

namespace App\Notifications;

use App\Models\AccomplishmentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly AccomplishmentReport $report)
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
            'type' => 'report',
            'text' => "{$this->report->user->name} submitted an accomplishment report for {$this->report->report_date->format('M j, Y')}.",
        ];
    }
}
