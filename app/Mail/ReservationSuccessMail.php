<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class ReservationSuccessMail
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    /**
     * Kirim email notifikasi via Mailtrap API (HTTP, bukan SMTP).
     * Digunakan karena port SMTP diblokir di lingkungan lokal.
     */
    public function sendViaApi(): void
    {
        $apiKey     = config('mail.mailtrap_api_key');
        $inboxId    = config('mail.mailtrap_inbox_id');
        $isSandbox  = config('mail.mailtrap_sandbox', true);

        $emailsApi = MailtrapClient::initSendingEmails(
            apiKey: $apiKey,
            isSandbox: $isSandbox,
            inboxId: $isSandbox ? (int) $inboxId : null,
        );

        $reservation = $this->reservation;
        $statusLabel = $reservation->status === 'approved'
            ? 'Disetujui Otomatis ✅'
            : 'Menunggu Persetujuan Admin ⏳';

        $tanggal = \Illuminate\Support\Carbon::parse($reservation->tanggal)
            ->locale('id')
            ->isoFormat('dddd, D MMMM YYYY');

        $htmlBody = view('emails.reservation-success', compact('reservation'))->render();

        $textBody = implode("\n", [
            "Halo, {$reservation->user->name}!",
            "",
            "Reservasi ruangan Anda telah berhasil diajukan.",
            "",
            "Ruangan   : {$reservation->room->nama} (Lantai {$reservation->room->lantai})",
            "Tanggal   : {$tanggal}",
            "Waktu     : {$reservation->jam_mulai} – {$reservation->jam_selesai} WIB",
            "Tujuan    : {$reservation->tujuan}",
            "Status    : {$statusLabel}",
            "",
            "Terima kasih,",
            "Tim PINTU TULT",
        ]);

        $subject = "[PINTU] Reservasi {$reservation->room->nama} – {$statusLabel}";

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
