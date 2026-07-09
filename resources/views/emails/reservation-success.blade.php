<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Berhasil – SIRAT</title>
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
                                            ✅ &nbsp; Reservasi Disetujui Otomatis
                                        </span>
                                    @else
                                        <span style="display:inline-block; background:#fef9c3; color:#a16207; padding:8px 22px; border-radius:999px; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                            ⏳ &nbsp; Menunggu Persetujuan Admin
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
                            @if($reservation->status === 'approved')
                                Reservasi ruangan Anda telah <strong style="color:#15803d;">berhasil dibuat dan langsung disetujui</strong>. Berikut adalah rincian reservasi Anda:
                            @else
                                Pengajuan reservasi ruangan Anda telah <strong style="color:#a16207;">berhasil dikirim</strong> dan sedang menunggu persetujuan dari admin. Berikut rinciannya:
                            @endif
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
                                    <p style="margin:2px 0 0; font-size:13px; color:#6b7280;">Lantai {{ $reservation->room->lantai }} – Kapasitas {{ $reservation->room->kapasitas }} orang</p>
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

                            {{-- Keterangan (hanya tampil jika ada) --}}
                            @if($reservation->keterangan)
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Catatan</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:14px; color:#374151; font-style:italic;">{{ $reservation->keterangan }}</p>
                                </td>
                            </tr>
                            @endif

                            {{-- Status --}}
                            <tr>
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Status</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    @if($reservation->status === 'approved')
                                        <span style="display:inline-block; background:#dcfce7; color:#15803d; padding:4px 12px; border-radius:999px; font-size:13px; font-weight:700;">Disetujui</span>
                                    @else
                                        <span style="display:inline-block; background:#fef9c3; color:#a16207; padding:4px 12px; border-radius:999px; font-size:13px; font-weight:700;">Pending</span>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                {{-- Catatan tambahan jika pending --}}
                @if($reservation->status === 'pending')
                <tr>
                    <td style="padding: 20px 40px 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb; border-radius:10px; border:1px solid #fde68a;">
                            <tr>
                                <td style="padding:14px 18px;">
                                    <p style="margin:0; font-size:13px; color:#92400e; line-height:1.6;">
                                        ⚠️ &nbsp; Reservasi ruangan di lantai ini memerlukan persetujuan dari Admin. Anda akan mendapatkan notifikasi email lanjutan saat status reservasi diperbarui.
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
