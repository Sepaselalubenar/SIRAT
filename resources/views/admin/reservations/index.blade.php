@extends('layouts.admin')

@section('title', 'Data Reservasi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Semua Data Reservasi</h1>
        <p class="text-gray-500 mt-1">Lihat dan kelola seluruh riwayat reservasi ruangan oleh dosen/pegawai.</p>
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

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold border-b border-gray-150">
                        <th class="py-4 px-6 w-16">No</th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'room', 'order' => ($sortBy === 'room' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Ruangan
                                @if($sortBy === 'room')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'user', 'order' => ($sortBy === 'user' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Pemesan
                                @if($sortBy === 'user')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'created_at', 'order' => ($sortBy === 'created_at' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Diajukan Pada
                                @if($sortBy === 'created_at')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'tanggal', 'order' => ($sortBy === 'tanggal' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Tanggal
                                @if($sortBy === 'tanggal')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'waktu', 'order' => ($sortBy === 'waktu' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Waktu
                                @if($sortBy === 'waktu')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'tujuan', 'order' => ($sortBy === 'tujuan' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Tujuan
                                @if($sortBy === 'tujuan')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-6">
                            <a href="{{ route('admin.reservations.index', ['sort_by' => 'status', 'order' => ($sortBy === 'status' && $order === 'asc') ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 hover:text-gray-900 group transition-colors duration-150">
                                Status
                                @if($sortBy === 'status')
                                    @if($order === 'asc')
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @else
                                    <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($reservations as $index => $reservation)
                        @php
                            $isPast = \Illuminate\Support\Carbon::parse($reservation->tanggal . ' ' . $reservation->jam_selesai)->isPast();
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors duration-150">
                            <td class="py-4 px-6 text-gray-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold">
                                {{ $reservation->room->nama ?? '-' }}
                                <span class="block text-xs font-normal text-gray-400">Lantai {{ $reservation->room->lantai ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-900">{{ $reservation->user->name ?? '-' }}</span>
                                <span class="block text-xs text-gray-400">{{ $reservation->user->nip ? ucfirst($reservation->user->role) . ' (NIP: ' . $reservation->user->nip . ')' : 'Admin' }}</span>
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                {{ $reservation->created_at->translatedFormat('d M Y H:i') }} WIB
                            </td>
                            <td class="py-4 px-6">
                                {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-6 font-medium">
                                {{ substr($reservation->jam_mulai, 0, 5) }} - {{ substr($reservation->jam_selesai, 0, 5) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $reservation->tujuan }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500 max-w-xs truncate">
                                {{ $reservation->keterangan ?: '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($reservation->status === 'approved')
                                    @if($isPast)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span> Disetujui
                                        </span>
                                    @endif
                                @elseif($reservation->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span> Pending
                                    </span>
                                @elseif($reservation->status === 'cancelled')
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Dibatalkan
                                        </span>
                                        @if($reservation->alasan_pembatalan)
                                            <span class="block text-xxs text-amber-500 mt-1 italic max-w-[150px] truncate" title="{{ $reservation->alasan_pembatalan }}">
                                                Alasan: "{{ $reservation->alasan_pembatalan }}"
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span> Ditolak
                                        </span>
                                        @if($reservation->alasan_penolakan)
                                            <span class="block text-xxs text-red-400 mt-1 italic max-w-[150px] truncate" title="{{ $reservation->alasan_penolakan }}">
                                                Alasan: "{{ $reservation->alasan_penolakan }}"
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($reservation->status === 'approved' && $isPast)
                                    <span class="text-xs text-gray-400 italic">Selesai</span>
                                @elseif($reservation->status !== 'cancelled')
                                    <button
                                        type="button"
                                        onclick="openCancelModal({{ $reservation->id }}, '{{ $reservation->group_id }}', '{{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('d M Y') }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors duration-150 cursor-pointer"
                                    >
                                        Batalkan
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sudah dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 px-6 text-center text-gray-400 italic">
                                Belum ada data reservasi ruangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Batalkan Reservasi --}}
<div id="modal-cancel" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Batalkan Reservasi</h3>
            <button type="button" onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
        </div>

        <form id="form-cancel" method="POST" class="p-6 space-y-4">
            @csrf
            <p class="text-sm text-gray-600">Pembatalan akan mengubah status reservasi menjadi <strong class="text-amber-700">Dibatalkan</strong> dan mengirim email notifikasi ke pengguna.</p>
            
            {{-- Cancel type selection for multi-day reservations --}}
            <div id="cancel-type-container" class="hidden bg-amber-50/50 border border-amber-100 rounded-xl p-3.5 space-y-2">
                <label class="block text-xs font-bold text-amber-800 uppercase tracking-wider">Pilihan Pembatalan</label>
                <div class="space-y-2.5">
                    <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="cancel_type" value="single" class="w-4 h-4 text-amber-600 border-gray-300 focus:ring-amber-500 mr-2.5">
                        <span>Hanya untuk tanggal <strong id="cancel-single-date"></strong></span>
                    </label>
                    <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="cancel_type" value="all" class="w-4 h-4 text-amber-600 border-gray-300 focus:ring-amber-500 mr-2.5" checked>
                        <span>Seluruh rangkaian reservasi multi-hari ini</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
                <textarea
                    name="alasan_pembatalan"
                    id="cancel-alasan"
                    required
                    rows="3"
                    class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    placeholder="Tulis alasan pembatalan..."
                ></textarea>
            </div>

            <div class="pt-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeCancelModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold transition text-sm">
                    Batalkan Reservasi
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .text-xxs {
        font-size: 0.65rem;
    }
</style>

<script>
    function openCancelModal(reservationId, groupId, formattedDate) {
        const form = document.getElementById('form-cancel');
        form.action = `/admin/reservations/${reservationId}/cancel`;

        // Clear textarea on each open
        document.getElementById('cancel-alasan').value = '';

        // Tampilkan/sembunyikan opsi pembatalan jika memiliki group_id
        const container = document.getElementById('cancel-type-container');
        const singleDateSpan = document.getElementById('cancel-single-date');
        
        if (groupId && groupId.trim() !== '') {
            container.classList.remove('hidden');
            singleDateSpan.textContent = formattedDate;
            // Set default value to 'all'
            form.querySelector('input[name="cancel_type"][value="all"]').checked = true;
        } else {
            container.classList.add('hidden');
        }

        const modal = document.getElementById('modal-cancel');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeCancelModal() {
        const modal = document.getElementById('modal-cancel');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Close modal when clicking backdrop
    document.getElementById('modal-cancel').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });
</script>
@endsection
