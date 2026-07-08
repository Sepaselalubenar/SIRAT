<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class ReservationStatusMail
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    /**
     * Kirim email notifikasi status reservasi via Mailtrap API (HTTP, bukan SMTP).
     */
    public function sendViaApi(): void
    {
        $apiKey     = config('mail.mailtrap_api_key');
        $inboxId    = config('mail.mailtrap_inbox_id');
        $isSandbox  = (bool) config('mail.mailtrap_sandbox', true);

        $emailsApi = MailtrapClient::initSendingEmails(
            apiKey: $apiKey,
            isSandbox: $isSandbox,
            inboxId: $isSandbox ? (int) $inboxId : null,
        );

        $reservation = $this->reservation;
        $statusLabel = $reservation->status === 'approved'
            ? 'Disetujui'
            : 'Ditolak';

        $tanggal = \Illuminate\Support\Carbon::parse($reservation->tanggal)
            ->locale('id')
            ->isoFormat('dddd, D MMMM YYYY');

        $htmlBody = view('emails.reservation-status', compact('reservation'))->render();

        $textBody = implode("\n", [
            "Halo, {$reservation->user->name}!",
            "",
            "Status pengajuan reservasi ruangan Anda telah diperbarui oleh Admin.",
            "",
            "Ruangan   : {$reservation->room->nama}",
            "Tanggal   : {$tanggal}",
            "Waktu     : {$reservation->jam_mulai} – {$reservation->jam_selesai} WIB",
            "Tujuan    : {$reservation->tujuan}",
            "Status    : {$statusLabel}",
        ]);

        if ($reservation->status === 'rejected' && $reservation->alasan_penolakan) {
            $textBody .= "\nAlasan Penolakan: \"{$reservation->alasan_penolakan}\"";
        }

        $textBody .= "\n\nTerima kasih,\nTim PINTU TULT";

        $subject = "[PINTU] Status Reservasi {$reservation->room->nama} – {$statusLabel}";

        $email = (new MailtrapEmail())
            ->from(new Address(
                config('mail.from.address', 'no-reply@telkomuniversity.ac.id'),
                config('mail.from.name', 'PINTU TULT')
            ))
            ->to(new Address($reservation->user->email, $reservation->user->name))
            ->subject($subject)
            ->html($htmlBody)
            ->text($textBody);

        $emailsApi->send($email);
    }
}
