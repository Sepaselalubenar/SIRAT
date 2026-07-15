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

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">
                Reservasi Menunggu Persetujuan
            </h2>
        </div>

        @forelse($pendingList ?? [] as $reservation)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b py-4 last:border-b-0 gap-4">
                <div>
                    <h3 class="font-semibold">
                        {{ $reservation->room->nama ?? '-' }}
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ $reservation->user->name ?? '-' }} &middot;
                        {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                        &middot; {{ $reservation->tujuan }}
                        &middot; <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">{{ substr($reservation->jam_mulai, 0, 5) }} - {{ substr($reservation->jam_selesai, 0, 5) }}</span>
                        &middot; <span class="text-xs text-gray-400">Diajukan: {{ $reservation->created_at->translatedFormat('d M Y H:i') }} WIB</span>
                    </p>
                    @if($reservation->keterangan)
                        <p class="text-xs text-gray-400 mt-1 italic">Catatan: "{{ $reservation->keterangan }}"</p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui reservasi ini?');">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3.5 py-2 rounded-xl font-semibold transition cursor-pointer">
                            Setujui
                        </button>
                    </form>
                    <button type="button" onclick="openRejectModal({{ $reservation->id }})" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3.5 py-2 rounded-xl font-semibold transition cursor-pointer">
                        Tolak
                    </button>
                </div>
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b py-4 last:border-b-0 gap-2">
                <div>
                    <h3 class="font-semibold">
                        {{ $reservation->room->nama ?? '-' }}
                        <span class="text-gray-400 font-normal text-sm">&middot; Lantai {{ $reservation->room->lantai ?? '-' }}</span>
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ $reservation->user->name ?? '-' }} &middot;
                        {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                        &middot; {{ $reservation->tujuan }}
                        &middot; <span class="text-xs text-gray-400">Diajukan: {{ $reservation->created_at->translatedFormat('d M Y H:i') }} WIB</span>
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

<!-- Modal Tolak Reservasi -->
<div id="modal-reject" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Tolak Reservasi</h3>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
        </div>

        <form id="form-reject" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                <textarea name="alasan_penolakan" required rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tulis alasan penolakan..."></textarea>
            </div>

            <div class="pt-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition text-sm">
                    Tolak Reservasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(reservationId) {
        const form = document.getElementById('form-reject');
        form.action = `/admin/reservations/${reservationId}/reject`;
        
        const modal = document.getElementById('modal-reject');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeRejectModal() {
        const modal = document.getElementById('modal-reject');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
</script>

@endsection
