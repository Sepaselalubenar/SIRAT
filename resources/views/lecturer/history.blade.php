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
        <table class="w-full text-left">
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
                        $isPast = \Illuminate\Support\Carbon::parse($r->tanggal)->isPast();
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
