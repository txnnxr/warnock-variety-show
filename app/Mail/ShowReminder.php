<?php

namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShowReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invite $invite)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "See you soon: Warnock Variety Show - {$this->invite->show->name}");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.show-reminder');
    }
}
