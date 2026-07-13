<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MultiDayReservationSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param \Illuminate\Support\Collection|\App\Models\Reservation[] $reservations
     */
    public function __construct(public $reservations) {}

    public function envelope(): Envelope
    {
        $first = $this->reservations->first();
        $statusLabel = $first->status === 'approved'
            ? 'Disetujui Otomatis'
            : 'Menunggu Persetujuan Admin';

        return new Envelope(
            subject: "[SIRAT] Reservasi Multi-Hari {$first->room->nama} - {$statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.multi-day-reservation-success',
            with: [
                'reservations' => $this->reservations,
                'firstReservation' => $this->reservations->first(),
            ],
        );
    }
}
