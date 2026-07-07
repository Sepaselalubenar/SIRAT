@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard Admin
        </h1>

        <p class="text-gray-500 mt-2">
            Kelola ruangan dan reservasi Sistem Reservasi Area TULT
        </p>
    </div>

    <div class="grid grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-gray-500">
                Total Ruangan
            </h3>
            <p class="text-4xl font-bold text-blue-600 mt-4">
                {{ $totalRooms ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-gray-500">
                Menunggu Approval
            </h3>
            <p class="text-4xl font-bold text-yellow-500 mt-4">
                {{ $pendingReservations ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-gray-500">
                Reservasi Disetujui
            </h3>
            <p class="text-4xl font-bold text-green-600 mt-4">
                {{ $approvedReservations ?? 0 }}
            </p>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">
                Reservasi Menunggu Persetujuan
            </h2>
        </div>

        @forelse($pendingList ?? [] as $reservation)
            <div class="flex items-center justify-between border-b py-4 last:border-b-0">
                <div>
                    <h3 class="font-semibold">
                        {{ $reservation->room->nama ?? '-' }}
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ $reservation->user->name ?? '-' }} &middot;
                        {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                        &middot; {{ $reservation->tujuan }}
                    </p>
                </div>

                <span class="text-yellow-500 font-semibold text-sm">
                    Menunggu Approval
                </span>
            </div>
        @empty
            <p class="text-gray-500">
                Belum ada reservasi yang menunggu persetujuan.
            </p>
        @endforelse

    </div>

    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">
                Ruangan yang Sedang / Akan Dipakai
            </h2>
        </div>

        @forelse($approvedList ?? [] as $reservation)
            <div class="flex items-center justify-between border-b py-4 last:border-b-0">
                <div>
                    <h3 class="font-semibold">
                        {{ $reservation->room->nama ?? '-' }}
                        <span class="text-gray-400 font-normal text-sm">&middot; Lantai {{ $reservation->room->lantai ?? '-' }}</span>
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ $reservation->user->name ?? '-' }} &middot;
                        {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                        &middot; {{ $reservation->tujuan }}
                    </p>
                </div>

                <span class="text-green-600 font-semibold text-sm">
                    Disetujui
                </span>
            </div>
        @empty
            <p class="text-gray-500">
                Belum ada ruangan yang sedang dipakai.
            </p>
        @endforelse

    </div>

</div>

@endsection
