<?php

namespace App\Notifications;

use App\Models\MeetingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MeetingScheduledNotification extends Notification
{
    use Queueable;

    protected $meeting;

    public function __construct(MeetingRequest $meeting)
    {
        $this->meeting = $meeting;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'meeting_scheduled',
            'meeting_id' => $this->meeting->id,
            'project_id' => $this->meeting->project_id,
            'round_id' => $this->meeting->incubator_round_id,
            'requested_for' => optional($this->meeting->requested_for)->toDateTimeString(),
            'mode' => $this->meeting->meeting_mode,
            'duration_minutes' => $this->meeting->duration_minutes,
            'agenda' => $this->meeting->agenda,
        ];
    }
}

