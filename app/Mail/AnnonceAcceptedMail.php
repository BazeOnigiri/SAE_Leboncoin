<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnonceAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $titreAnnonce;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $titreAnnonce)
    {
        $this->user = $user;
        $this->titreAnnonce = $titreAnnonce;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre annonce a été validée !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.annonce-accepted',
            with: [
                'user' => $this->user,
                'titreAnnonce' => $this->titreAnnonce,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
