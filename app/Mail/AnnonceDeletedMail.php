<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnonceDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $titreAnnonce;
    public ?string $motif;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $titreAnnonce, ?string $motif = null)
    {
        $this->user = $user;
        $this->titreAnnonce = $titreAnnonce;
        $this->motif = $motif;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre annonce a été rejetée',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.annonce-deleted',
            with: [
                'user' => $this->user,
                'titreAnnonce' => $this->titreAnnonce,
                'motif' => $this->motif,
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
