<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMultiDayReservationRequestMail extends Mailable
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
            : 'Menunggu Persetujuan';

        return new Envelope(
            subject: "[SIRAT Admin] Pengajuan Reservasi Multi-Hari: {$first->room->nama} - {$statusLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-multi-day-reservation-request',
            with: [
                'reservations' => $this->reservations,
                'firstReservation' => $this->reservations->first(),
            ],
        );
    }
}
