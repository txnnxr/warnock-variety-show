<?php

namespace App\Mail;

use App\Models\Invite;
use App\Models\Show;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShowRecap extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300];

    public function __construct(public Invite $invite)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Thanks for coming to {$this->invite->show->name}!");
    }

    public function content(): Content
    {
        $show = $this->invite->show;

        return new Content(
            markdown: 'mail.show-recap',
            with: [
                'lineup' => $show->lineup()->with('person')->get(),
                'photos' => $show->photos()->take(3)->get(),
                'photoCount' => $show->photos()->count(),
                'nextShow' => Show::upcoming()->where('canceled', false)->orderBy('date')->first(),
            ],
        );
    }
}
