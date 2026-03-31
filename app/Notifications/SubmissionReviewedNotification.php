<?php

namespace App\Notifications;

use App\Models\Evaluation;
use App\Models\TaskSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionReviewedNotification extends Notification
{
    use Queueable;

    protected $submission;
    protected $evaluation;

    public function __construct(TaskSubmission $submission, Evaluation $evaluation)
    {
        $this->submission = $submission;
        $this->evaluation = $evaluation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'submission_reviewed',
            'submission_id' => $this->submission->id,
            'decision' => $this->evaluation->decision,
            'comments' => $this->evaluation->comments,
            'message' => 'Your task submission has been reviewed.',
        ];
    }
}
