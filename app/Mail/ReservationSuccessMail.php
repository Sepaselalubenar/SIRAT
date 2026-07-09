<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        $statusLabel = $this->reservation->status === 'approved'
            ? 'Disetujui Otomatis'
            : 'Menunggu Persetujuan Admin';

        return new Envelope(
            subject: "[SIRAT] Reservasi {$this->reservation->room->nama} - {$statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-success',
            with: ['reservation' => $this->reservation],
        );
    }
}
