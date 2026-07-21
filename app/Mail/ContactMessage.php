<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $submission)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->submission['email']],
            subject: 'New contact form message from ' . $this->submission['name'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
            with: [
                'submission' => $this->submission,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
