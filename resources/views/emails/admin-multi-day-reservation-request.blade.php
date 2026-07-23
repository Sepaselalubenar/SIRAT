<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Reservasi Multi-Hari Baru – SIRAT Admin</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family: 'Segoe UI', Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding: 40px 0;">
    <tr>
        <td align="center">

            {{-- ===== Kartu Utama ===== --}}
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); max-width:600px; width:100%;">

                {{-- Header --}}
                <tr>
                    <td style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 40px 40px 32px; text-align:center;">
                        <div style="display:inline-block; background:rgba(255,255,255,0.15); border-radius:50%; padding:14px; margin-bottom:16px;">
                            <span style="font-size:32px; display:block; line-height:1;">📅</span>
                        </div>
                        <h1 style="color:#ffffff; margin:0 0 6px 0; font-size:26px; font-weight:700; letter-spacing:-0.5px;">SIRAT ADMIN</h1>
                        <p style="color:#93c5fd; margin:0; font-size:14px;">Notifikasi Permintaan Reservasi Multi-Hari</p>
                    </td>
                </tr>

                {{-- Status Badge --}}
                <tr>
                    <td style="padding: 0 40px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding: 24px 0 0 0;">
                                    @if($firstReservation->status === 'approved')
                                        <span style="display:inline-block; background:#dcfce7; color:#15803d; padding:8px 22px; border-radius:999px; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                            ✅ &nbsp; Reservasi Disetujui Otomatis
                                        </span>
                                    @else
                                        <span style="display:inline-block; background:#fef9c3; color:#a16207; padding:8px 22px; border-radius:999px; font-size:14px; font-weight:700; letter-spacing:0.3px;">
                                            ⏳ &nbsp; Membutuhkan Persetujuan Admin
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Greeting & Description --}}
                <tr>
                    <td style="padding: 28px 40px 8px;">
                        <p style="margin:0; font-size:16px; color:#374151;">Halo Admin,</p>
                        <p style="margin:10px 0 0; font-size:15px; color:#6b7280; line-height:1.6;">
                            Terdapat pengajuan reservasi ruangan **Multi-Hari (Sehari Penuh)** baru dari dosen/staf yang memerlukan perhatian Anda. Berikut adalah rincian pengajuan tersebut:
                        </p>
                    </td>
                </tr>

                {{-- Detail Pemohon --}}
                <tr>
                    <td style="padding: 10px 40px 0;">
                        <h3 style="margin: 0 0 8px 0; font-size: 14px; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Detail Pemohon</h3>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; margin-bottom: 20px;">
                            <tr>
                                <td style="padding:10px 20px; border-bottom:1px solid #e2e8f0; width:30%; font-size: 13px; color: #6b7280; font-weight: 600;">Nama</td>
                                <td style="padding:10px 20px; border-bottom:1px solid #e2e8f0; font-size: 13px; color: #111827; font-weight: 700;">{{ $firstReservation->user->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 20px; border-bottom:1px solid #e2e8f0; font-size: 13px; color: #6b7280; font-weight: 600;">Email / NIP</td>
                                <td style="padding:10px 20px; border-bottom:1px solid #e2e8f0; font-size: 13px; color: #111827;">{{ $firstReservation->user->email }} / {{ $firstReservation->user->nip ?? '-' }}</td>
                            </tr>
                            @if($firstReservation->user->phone_number)
                            <tr>
                                <td style="padding:10px 20px; font-size: 13px; color: #6b7280; font-weight: 600;">No. Telepon</td>
                                <td style="padding:10px 20px; font-size: 13px; color: #111827;">{{ $firstReservation->user->phone_number }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>

                {{-- Detail Reservasi --}}
                <tr>
                    <td style="padding: 10px 40px 0;">
                        <h3 style="margin: 0 0 8px 0; font-size: 14px; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Detail Reservasi</h3>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">

                            {{-- Nama Ruangan --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff; width:30%;">
                                    <p style="margin:0; font-size:12px; color:#1e3a8a; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Ruangan</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827; font-weight:700;">{{ $firstReservation->room->nama }}</p>
                                    <p style="margin:2px 0 0; font-size:13px; color:#6b7280;">Lantai {{ $firstReservation->room->lantai }} – Kapasitas {{ $firstReservation->room->kapasitas }} orang</p>
                                </td>
                            </tr>

                            {{-- Tanggal-Tanggal --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff; vertical-align: top;">
                                    <p style="margin:0; font-size:12px; color:#1e3a8a; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Daftar Tanggal</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <ul style="margin:0; padding:0 0 0 20px; font-size:15px; color:#111827; font-weight:600; line-height:1.6;">
                                        @foreach($reservations as $res)
                                            <li>{{ \Illuminate\Support\Carbon::parse($res->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>

                            {{-- Waktu --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#1e3a8a; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Waktu</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827; font-weight:600;">
                                        {{ $firstReservation->jam_mulai }} – {{ $firstReservation->jam_selesai }} WIB (Sehari Penuh)
                                    </p>
                                </td>
                            </tr>

                            {{-- Tujuan --}}
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#1e3a8a; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Tujuan</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:15px; color:#111827;">{{ $firstReservation->tujuan }}</p>
                                </td>
                            </tr>

                            {{-- Keterangan (hanya tampil jika ada) --}}
                            @if($firstReservation->keterangan)
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#1e3a8a; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Catatan</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    <p style="margin:0; font-size:14px; color:#374151; font-style:italic;">{{ $firstReservation->keterangan }}</p>
                                </td>
                            </tr>
                            @endif

                            {{-- Status --}}
                            <tr>
                                <td style="padding:14px 20px; background:#eff6ff;">
                                    <p style="margin:0; font-size:12px; color:#1e3a8a; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Status</p>
                                </td>
                                <td style="padding:14px 20px;">
                                    @if($firstReservation->status === 'approved')
                                        <span style="display:inline-block; background:#dcfce7; color:#15803d; padding:4px 12px; border-radius:999px; font-size:13px; font-weight:700;">Disetujui Otomatis</span>
                                    @else
                                        <span style="display:inline-block; background:#fef9c3; color:#a16207; padding:4px 12px; border-radius:999px; font-size:13px; font-weight:700;">Menunggu Approval</span>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                {{-- Action Button --}}
                <tr>
                    <td align="center" style="padding: 32px 40px 10px;">
                        <a href="{{ url('/admin') }}" style="display:inline-block; background-color:#1e3a8a; color:#ffffff; padding:14px 30px; border-radius:8px; font-size:15px; font-weight:700; text-decoration:none; box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.2); transition: background-color 0.2s;">
                            Kelola di Dashboard Admin &rarr;
                        </a>
                    </td>
                </tr>

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
                            Email ini dikirim otomatis oleh sistem SIRAT untuk Administrator.
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
