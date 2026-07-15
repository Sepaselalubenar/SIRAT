<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MultiDayReservationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param \Illuminate\Support\Collection|\App\Models\Reservation[] $reservations
     * @param string $status
     */
    public function __construct(public $reservations, public string $status) {}

    public function envelope(): Envelope
    {
        $first = $this->reservations->first();
        $statusLabel = match($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan oleh Admin',
            default => ucfirst($this->status),
        };

        return new Envelope(
            subject: "[SIRAT] Status Reservasi Multi-Hari {$first->room->nama} - {$statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.multi-day-reservation-status',
            with: [
                'reservations' => $this->reservations,
                'firstReservation' => $this->reservations->first(),
                'status' => $this->status,
            ],
        );
    }
}
