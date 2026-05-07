<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InactiveUserReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $insight;
    public $type;

    public function __construct($user, $insight, $type)
    {
        $this->user = $user;
        $this->insight = $insight;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder of your learning progress',
        );
    }

    public function content(): Content
    {
        URL::forceRootUrl('https://eduma.oncloudtop.com');

        $avgProgress = $this->insight->avg_progress ?? 0;
        $inactiveDays = $this->insight->inactive_days ?? 0;
        $type = $inactiveDays > 14 ? 'inactive' : 'risk';

        return new Content(
            view: 'emails.inactive_user_reminder',
            with: [
                'user' => $this->user,
                'avgProgress' => $this->insight->avg_progress,
                'inactiveDays' => $this->insight->inactive_days,
                'type' => $this->type
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
