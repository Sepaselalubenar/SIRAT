@extends('layouts.admin')

@section('title', 'Data Reservasi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Semua Data Reservasi</h1>
        <p class="text-gray-500 mt-1">Lihat dan kelola seluruh riwayat reservasi ruangan oleh dosen.</p>
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
                        <tr class="hover:bg-gray-50/70 transition-colors duration-150">
                            <td class="py-4 px-6 text-gray-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold">
                                {{ $reservation->room->nama ?? '-' }}
                                <span class="block text-xs font-normal text-gray-400">Lantai {{ $reservation->room->lantai ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-900">{{ $reservation->user->name ?? '-' }}</span>
                                <span class="block text-xs text-gray-400">{{ $reservation->user->nip ? 'NIP: ' . $reservation->user->nip : 'Admin' }}</span>
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
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span> Disetujui
                                    </span>
                                @elseif($reservation->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span> Pending
                                    </span>
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
                                <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data reservasi ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors duration-150 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
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

<style>
    .text-xxs {
        font-size: 0.65rem;
    }
</style>
@endsection
