<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[SIRAT] Reservasi Anda Dibatalkan oleh Admin – {$this->reservation->room->nama}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-cancelled',
            with: ['reservation' => $this->reservation],
        );
    }
}
