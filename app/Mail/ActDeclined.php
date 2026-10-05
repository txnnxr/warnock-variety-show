<?php

namespace App\Mail;

use App\Models\SubmissionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActDeclined extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300];

    public function __construct(public SubmissionApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "About your act for {$this->application->show->name}");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.act-declined');
    }
}
