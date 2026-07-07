@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard Dosen
        </h1>

        <p class="text-gray-500 mt-2">
            Selamat datang di Sistem Reservasi Area TULT
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-gray-500">
                Total Reservasi Saya
            </h3>
            <p class="text-4xl font-bold text-blue-600 mt-4">
                {{ $totalReservations }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-gray-500">
                Menunggu Approval
            </h3>
            <p class="text-4xl font-bold text-yellow-500 mt-4">
                {{ $pendingReservations }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-gray-500">
                Reservasi Disetujui
            </h3>
            <p class="text-4xl font-bold text-green-600 mt-4">
                {{ $approvedReservations }}
            </p>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">
                Reservasi Terbaru Saya
            </h2>
            <a href="/history" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Lihat Semua Riwayat &rsaquo;
            </a>
        </div>

        @forelse($recentReservations ?? [] as $reservation)
            <div class="flex items-center justify-between border-b py-4 last:border-b-0">
                <div>
                    <h3 class="font-semibold">
                        {{ $reservation->room->nama ?? '-' }}
                        <span class="text-gray-400 font-normal text-sm">&middot; Lantai {{ $reservation->room->lantai ?? '-' }}</span>
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }} &middot;
                        {{ substr($reservation->jam_mulai, 0, 5) }} - {{ substr($reservation->jam_selesai, 0, 5) }} &middot;
                        Tujuan: {{ $reservation->tujuan }}
                    </p>
                    @if($reservation->status === 'rejected' && $reservation->alasan_penolakan)
                        <p class="text-xs text-red-500 mt-1 italic">Alasan penolakan: "{{ $reservation->alasan_penolakan }}"</p>
                    @endif
                </div>

                <div>
                    @if($reservation->status === 'approved')
                        <span class="text-green-600 font-semibold text-sm">Disetujui</span>
                    @elseif($reservation->status === 'pending')
                        <span class="text-yellow-500 font-semibold text-sm">Menunggu Approval</span>
                    @else
                        <span class="text-red-500 font-semibold text-sm">Ditolak</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">
                Anda belum memiliki riwayat reservasi ruangan.
            </p>
        @endforelse

    </div>

</div>

@endsection