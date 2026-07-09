<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        $statusLabel = $this->reservation->status === 'approved'
            ? 'Disetujui'
            : 'Ditolak';

        return new Envelope(
            subject: "[SIRAT] Status Reservasi {$this->reservation->room->nama} - {$statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-status',
            with: ['reservation' => $this->reservation],
        );
    }
}
