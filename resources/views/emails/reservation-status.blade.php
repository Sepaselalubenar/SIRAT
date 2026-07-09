<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Status Reservasi – SIRAT</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family: 'Segoe UI', Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding: 40px 0;">
    <tr>
        <td align="center">

            {{-- ===== Kartu Utama ===== --}}
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); max-width:600px; width:100%;">

                {{-- Header --}}
                <tr>
                    <td style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); padding: 40px 40px 32px; text-align:center;">
                        <div style="display:inline-block; background:rgba(255,255,255,0.15); border-radius:50%; padding:14px; margin-bottom:16px;">
                            <span style="font-size:32px; display:block; line-height:1;">🏛️</span>
                        </div>
                        <h1 style="color:#ffffff; margin:0 0 6px 0; font-size:26px; font-weight:700; letter-spacing:-0.5px;">SIRAT</h1>
                        <p style="color:#bfdbfe; margin:0; font-size:14px;">Sistem Reservasi Ruangan FTE</p>
                    </td>
                </tr>

                {{-- Status Badge --}}
                <tr>
                    <td style="padding: 0 40px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding: 24px 0 0 0;">
                                    @if($reservation->status === 'approved')
                                        <span style="display:inline-block; background:#dcfce7; color:#15803d; padding:8px 22px; border-radius:999px; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                            ✅ &nbsp; Reservasi Disetujui
                                        </span>
                                    @else
                                        <span style="display:inline-block; background:#fee2e2; color:#b91c1c; padding:8px 22px; border-radius:999px; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                            ❌ &nbsp; Reservasi Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Greeting --}}
                <tr>
                    <td style="padding: 28px 40px 8px;">
                        <p style="margin:0; font-size:16px; color:#374151;">Halo, <strong>{{ $reservation->user->name }}</strong>,</p>
                        <p style="margin:10px 0 0; font-size:15px; color:#6b7280; line-height:1.6;">
                            Status pengajuan reservasi ruangan Anda telah diperbarui oleh Admin. Berikut adalah rincian terbarunya:
                        </p>
                    </td>
                </tr>

                {{-- Detail Card --}}
                <tr>
                    <td style="padding: 20px 40px 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">

                            {{-- Nama Ruangan --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff; width:40%;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Ruangan</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827; font-weight:700;">{{ $reservation->room->nama }}</p>
                                    <p style="margin:2px 0 0; font-size:13px; color:#6b7280;">Lantai {{ $reservation->room->lantai }}</p>
                                </td>
                            </tr>

                            {{-- Tanggal --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Tanggal</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827; font-weight:600;">
                                        {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                </td>
                            </tr>

                            {{-- Waktu --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Waktu</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827; font-weight:600;">
                                        {{ $reservation->jam_mulai }} – {{ $reservation->jam_selesai }} WIB
                                    </p>
                                </td>
                            </tr>

                            {{-- Tujuan --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Tujuan</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827;">{{ $reservation->tujuan }}</p>
                                </td>
                            </tr>

                            {{-- Status --}}
                            <tr>
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Status Akhir</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    @if($reservation->status === 'approved')
                                        <span style="display:inline-block; background:#dcfce7; color:#15803d; padding:4px 12px; border-radius:999px; font-size:13px; font-weight:700;">Disetujui</span>
                                    @else
                                        <span style="display:inline-block; background:#fee2e2; color:#b91c1c; padding:4px 12px; border-radius:999px; font-size:13px; font-weight:700;">Ditolak</span>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                {{-- Alasan Penolakan (hanya tampil jika status ditolak) --}}
                @if($reservation->status === 'rejected' && $reservation->alasan_penolakan)
                <tr>
                    <td style="padding: 20px 40px 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff5f5; border-radius:10px; border:1px solid #feb2b2;">
                            <tr>
                                <td style="padding:14px 18px;">
                                    <p style="margin:0; font-size:13px; color:#9b2c2c; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; mb-4">Alasan Penolakan Admin:</p>
                                    <p style="margin:6px 0 0; font-size:14px; color:#c53030; font-style:italic; line-height:1.6;">
                                        "{{ $reservation->alasan_penolakan }}"
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                {{-- Divider --}}
                <tr>
                    <td style="padding: 28px 40px 0;">
                        <hr style="border:none; border-top:1px solid #e2e8f0; margin:0;">
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding: 24px 40px 36px; text-align:center;">
                        <p style="margin:0 0 6px; font-size:13px; color:#9ca3af;">
                            Email ini dikirim otomatis oleh sistem. Jangan membalas email ini.
                        </p>
                        <p style="margin:0; font-size:13px; color:#9ca3af;">
                            &copy; {{ date('Y') }} SIRAT – Sistem Reservasi Ruangan FTE
                        </p>
                    </td>
                </tr>

            </table>
            {{-- End Kartu Utama --}}

        </td>
    </tr>
</table>

</body>
</html>
