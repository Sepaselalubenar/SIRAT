@extends('layouts.app')

@section('title', 'Riwayat Reservasi')

@section('content')

<h1 class="text-3xl font-bold text-gray-800">Riwayat Reservasi</h1>
<p class="text-gray-500 mt-1 mb-6">Daftar seluruh reservasi yang pernah Anda lakukan</p>

@if(session('success'))
    <div class="bg-green-50 text-green-700 rounded-lg p-4 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex gap-2 border-b mb-6 overflow-x-auto" id="status-tabs">
        <button type="button" class="status-tab whitespace-nowrap px-4 py-3 font-medium" data-status="semua">Semua</button>
        <button type="button" class="status-tab whitespace-nowrap px-4 py-3 font-medium" data-status="aktif">Aktif</button>
        <button type="button" class="status-tab whitespace-nowrap px-4 py-3 font-medium" data-status="pending">Menunggu Approval</button>
        <button type="button" class="status-tab whitespace-nowrap px-4 py-3 font-medium" data-status="selesai">Selesai</button>
        <button type="button" class="status-tab whitespace-nowrap px-4 py-3 font-medium" data-status="rejected">Ditolak</button>
    </div>

    @if($reservations->isEmpty())
        <p class="text-gray-400 text-center py-12">Belum ada riwayat reservasi.</p>
    @else
        <!-- Desktop/Tablet Version -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left" style="min-width: 800px;">
                <thead>
                    <tr class="text-gray-500 text-sm border-b">
                        <th class="py-3">Waktu Pemakaian</th>
                        <th class="py-3">Diajukan Pada</th>
                        <th class="py-3">Ruangan</th>
                        <th class="py-3">Tujuan</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $r)
                        @php
                            $isPast = \Illuminate\Support\Carbon::parse($r->tanggal . ' ' . $r->jam_selesai)->isPast();
                            $rowStatus = match(true) {
                                $r->status === 'pending' => 'pending',
                                $r->status === 'rejected' => 'rejected',
                                $r->status === 'approved' && $isPast => 'selesai',
                                $r->status === 'approved' && !$isPast => 'aktif',
                                default => 'semua',
                            };
                            $badge = match($r->status) {
                                'pending' => ['Menunggu Approval', 'bg-yellow-100 text-yellow-700'],
                                'approved' => $isPast ? ['Selesai', 'bg-gray-100 text-gray-600'] : ['Disetujui', 'bg-green-100 text-green-700'],
                                'rejected' => ['Ditolak', 'bg-red-100 text-red-700'],
                                default => [ucfirst($r->status), 'bg-gray-100 text-gray-600'],
                            };
                        @endphp
                        <tr class="history-row border-b last:border-b-0" data-status="{{ $rowStatus }}">
                            <td class="py-4">
                                <p class="font-medium">{{ \Illuminate\Support\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ substr($r->jam_mulai, 0, 5) }} - {{ substr($r->jam_selesai, 0, 5) }} WIB
                                </p>
                            </td>
                            <td class="py-4 text-gray-500 text-sm">
                                {{ $r->created_at->translatedFormat('d M Y H:i') }} WIB
                            </td>
                            <td class="py-4">
                                <p class="font-medium">{{ $r->room->nama ?? '-' }}</p>
                                <p class="text-gray-400 text-sm">Lantai {{ $r->room->lantai ?? '-' }}</p>
                            </td>
                            <td class="py-4">{{ $r->tujuan }}</td>
                            <td class="py-4">
                                <span class="text-xs font-semibold px-2 py-1 rounded {{ $badge[1] }}">{{ $badge[0] }}</span>
                            </td>
                            <td class="py-4 text-gray-500 text-sm max-w-xs">
                                @if($r->status === 'rejected' && $r->alasan_penolakan)
                                    <span class="text-red-500">Alasan: {{ $r->alasan_penolakan }}</span>
                                @else
                                    {{ $r->keterangan ?? '-' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Version -->
        <div class="md:hidden space-y-4">
            @foreach($reservations as $r)
                @php
                    $isPast = \Illuminate\Support\Carbon::parse($r->tanggal . ' ' . $r->jam_selesai)->isPast();
                    $rowStatus = match(true) {
                        $r->status === 'pending' => 'pending',
                        $r->status === 'rejected' => 'rejected',
                        $r->status === 'approved' && $isPast => 'selesai',
                        $r->status === 'approved' && !$isPast => 'aktif',
                        default => 'semua',
                    };
                    $badge = match($r->status) {
                        'pending' => ['Menunggu Approval', 'bg-yellow-100 text-yellow-700'],
                        'approved' => $isPast ? ['Selesai', 'bg-gray-100 text-gray-600'] : ['Disetujui', 'bg-green-100 text-green-700'],
                        'rejected' => ['Ditolak', 'bg-red-100 text-red-700'],
                        default => [ucfirst($r->status), 'bg-gray-100 text-gray-600'],
                    };
                @endphp
                <div class="history-row bg-gray-50 hover:bg-gray-100/50 border border-gray-150 rounded-xl p-4 transition duration-150" data-status="{{ $rowStatus }}">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ruangan</span>
                            <h4 class="font-bold text-gray-800 text-sm mt-0.5">{{ $r->room->nama ?? '-' }}</h4>
                            <p class="text-xs text-gray-500">Lantai {{ $r->room->lantai ?? '-' }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded shrink-0 {{ $badge[1] }}">{{ $badge[0] }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-2.5 my-2.5 border-y border-gray-200/60 text-xs">
                        <div>
                            <span class="text-[10px] text-gray-400 font-bold tracking-wider block mb-0.5">WAKTU PEMAKAIAN</span>
                            <p class="font-semibold text-gray-700">{{ \Illuminate\Support\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</p>
                            <p class="text-gray-500 mt-0.5">{{ substr($r->jam_mulai, 0, 5) }} - {{ substr($r->jam_selesai, 0, 5) }} WIB</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-gray-400 font-bold tracking-wider block mb-0.5">DIAJUKAN PADA</span>
                            <p class="font-semibold text-gray-700">{{ $r->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-gray-500 mt-0.5">{{ $r->created_at->translatedFormat('H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs">
                        <div class="flex items-start gap-1">
                            <span class="text-gray-400 font-semibold min-w-[75px] shrink-0">Tujuan:</span>
                            <span class="text-gray-700">{{ $r->tujuan }}</span>
                        </div>
                        <div class="flex items-start gap-1">
                            <span class="text-gray-400 font-semibold min-w-[75px] shrink-0">Keterangan:</span>
                            <span class="text-gray-700 break-words">
                                @if($r->status === 'rejected' && $r->alasan_penolakan)
                                    <span class="text-red-650 font-medium">Alasan Penolakan: {{ $r->alasan_penolakan }}</span>
                                @else
                                    {{ $r->keterangan ?? '-' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<script>
(function () {
    const tabs = document.querySelectorAll('.status-tab');
    const rows = document.querySelectorAll('.history-row');

    function applyFilter(status) {
        tabs.forEach(t => {
            const active = t.dataset.status === status;
            t.classList.toggle('text-blue-600', active);
            t.classList.toggle('border-b-2', active);
            t.classList.toggle('border-blue-600', active);
            t.classList.toggle('text-gray-500', !active);
        });

        rows.forEach(row => {
            if (status === 'semua') {
                row.style.display = '';
            } else {
                row.style.display = row.dataset.status === status ? '' : 'none';
            }
        });
    }

    tabs.forEach(tab => tab.addEventListener('click', () => applyFilter(tab.dataset.status)));

    if (tabs.length) applyFilter('semua');
})();
</script>

@endsection
