@extends('layouts.app')

@section('title', 'Reservasi Baru')

@section('content')

<nav class="text-sm text-gray-500 mb-2">
    <a href="/dashboard" class="text-blue-600">Reservasi Baru</a>
    <span class="mx-1">&rsaquo;</span>
    <span>Pilih Ruangan</span>
</nav>

<h1 class="text-3xl font-bold text-gray-800">Pilih Ruangan</h1>
<p class="text-gray-500 mt-1 mb-6">Pilih lokasi ruangan yang ingin Anda reservasi</p>

<div class="w-full">

    {{-- ================= Kolom kiri: tab lantai + daftar ruangan ================= --}}
    <div class="w-full">

        <div class="flex gap-2 border-b mb-6 overflow-x-auto" id="floor-tabs">
            @foreach($roomsByLantai as $lantai => $roomsInLantai)
                <button
                    type="button"
                    class="floor-tab whitespace-nowrap px-5 py-3 rounded-t-lg font-medium text-gray-500 hover:text-blue-600"
                    data-floor="{{ $lantai }}"
                >
                    Lantai {{ $lantai }} ({{ $roomsInLantai->count() }} Ruangan)
                </button>
            @endforeach
        </div>

        @foreach($roomsByLantai as $lantai => $roomsInLantai)
            <div class="floor-panel" data-floor-panel="{{ $lantai }}">

                <h3 class="font-semibold text-lg text-gray-700 mb-4">Lantai {{ $lantai }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

                    @foreach($roomsInLantai as $room)
                        @php
                            $photoUrls = $room->photos->pluck('url')->values();
                            $butuhApproval = true;
                            $statusLabel = match($room->status) {
                                'tersedia' => 'Tersedia',
                                'dipakai' => 'Dipakai',
                                'maintenance' => 'Maintenance',
                                default => ucfirst($room->status),
                            };
                            $statusBadgeClass = match($room->status) {
                                'tersedia' => 'bg-green-50 text-green-700 border-green-200',
                                'dipakai' => 'bg-gray-100 text-gray-700 border-gray-200',
                                'maintenance' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                            };

                            $bookedSlots = $room->reservations->map(fn($r) => [
                                'tanggal' => \Illuminate\Support\Carbon::parse($r->tanggal)->toDateString(),
                                'jam_mulai' => substr($r->jam_mulai, 0, 5),
                                'jam_selesai' => substr($r->jam_selesai, 0, 5),
                            ])->values();

                            $roomData = [
                                'id' => $room->id,
                                'nama' => $room->nama,
                                'jenis' => $room->jenis ?? '-',
                                'lantai' => $room->lantai,
                                'kapasitas' => $room->kapasitas,
                                'fasilitas' => $room->fasilitas ?? [],
                                'deskripsi' => $room->deskripsi ?? '-',
                                'status' => $room->status,
                                'butuh_approval' => $butuhApproval,
                                'photos' => $photoUrls,
                                'booked' => $bookedSlots,
                             ];
                        @endphp

                        <div class="room-card bg-white rounded-xl shadow hover:shadow-xl transition"
                             data-room='@json($roomData)'
                        >
                            @if($photoUrls->isNotEmpty())
                                <img src="{{ $photoUrls->first() }}" class="rounded-t-xl h-44 w-full object-cover">
                            @else
                                <div class="rounded-t-xl h-44 w-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                    Belum ada foto
                                </div>
                            @endif

                            <div class="p-5 flex flex-col justify-between h-[calc(100%-11rem)]">
                                <div>
                                    <h2 class="font-bold text-lg text-gray-800">{{ $room->nama }}</h2>
                                    <p class="text-gray-500 text-sm mt-1">Lantai {{ $room->lantai }}</p>
                                    <p class="text-gray-500 text-sm">Kapasitas: {{ $room->kapasitas }} orang</p>

                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusBadgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                        @if($butuhApproval)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                Perlu Approval
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    class="btn-pilih-ruangan block text-center w-full text-white mt-4 py-3 rounded-lg
                                        {{ $room->status === 'tersedia' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed' }}"
                                    {{ $room->status !== 'tersedia' ? 'disabled' : '' }}
                                >
                                    Pilih
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach

    </div>

</div>

{{-- ================= Modal Detail Ruangan ================= --}}
<div id="room-modal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-bold" id="modal-title">Detail Ruangan</h3>
            <button type="button" id="modal-close" class="text-2xl text-gray-400 hover:text-gray-700">&times;</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

            {{-- Info --}}
            <div>
                {{-- State 1: Detail Ruangan --}}
                <div id="modal-content-details">
                    <h4 class="text-lg font-bold" id="modal-nama"></h4>
                    <span class="inline-block bg-blue-50 text-blue-600 text-xs font-semibold px-2 py-1 rounded mt-1" id="modal-lantai-badge"></span>
                    <p class="text-gray-500 mt-2" id="modal-kapasitas"></p>

                    <div class="border-t mt-4 pt-4">
                        <p class="font-semibold mb-2">Informasi Ruangan</p>
                        <div class="flex justify-between text-sm py-1">
                            <span class="text-gray-500">Jenis Ruangan</span>
                            <span id="modal-jenis"></span>
                        </div>
                        <div class="flex justify-between text-sm py-1">
                            <span class="text-gray-500">Lokasi</span>
                            <span id="modal-lokasi"></span>
                        </div>
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <p class="font-semibold mb-2">Fasilitas</p>
                        <ul id="modal-fasilitas" class="text-sm space-y-1"></ul>
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <p class="font-semibold mb-2">Keterangan</p>
                        <p class="text-sm text-gray-500" id="modal-deskripsi"></p>
                    </div>

                    <button type="button" id="modal-pilih-ruangan" class="w-full bg-blue-600 text-white rounded-lg py-3 mt-6 hover:bg-blue-700">
                        Pilih Ruangan Ini
                    </button>
                </div>

                {{-- State 2: Form Reservasi --}}
                <div id="modal-content-form" class="hidden">
                    <button type="button" id="modal-back-to-details" class="mb-4 inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-semibold transition">
                        &larr; Kembali ke Detail Ruangan
                    </button>

                    <form method="POST" action="/reservation/store" id="reservation-form">
                        @csrf

                        <input type="hidden" name="room_id" id="input-room-id">

                        <div id="ringkasan-terisi" class="bg-blue-50 rounded-xl p-4 mb-4">
                            <p class="font-semibold" id="ringkasan-nama-ruangan"></p>
                            <p class="text-gray-500 text-sm" id="ringkasan-lantai-ruangan"></p>
                        </div>

                        @php
                            $oldTipe = old('tipe_reservasi', 'biasa');
                        @endphp

                        <!-- Tipe Reservasi -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Reservasi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 {{ $oldTipe === 'biasa' ? 'border-blue-600 bg-blue-50/50' : 'border-gray-200' }}" id="tipe-biasa-label">
                                    <input type="radio" name="tipe_reservasi" value="biasa" @checked($oldTipe === 'biasa') class="sr-only" id="radio-tipe-biasa">
                                    <span class="text-lg mb-1">⏰</span>
                                    <span class="text-xs font-semibold text-gray-800">Jam Spesifik</span>
                                    <span class="text-[10px] text-gray-500">Pilih jam tertentu</span>
                                </label>
                                <label class="border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 {{ $oldTipe === 'sehari_penuh' ? 'border-blue-600 bg-blue-50/50' : 'border-gray-200' }}" id="tipe-sehari-penuh-label">
                                    <input type="radio" name="tipe_reservasi" value="sehari_penuh" @checked($oldTipe === 'sehari_penuh') class="sr-only" id="radio-tipe-sehari-penuh">
                                    <span class="text-lg mb-1">📅</span>
                                    <span class="text-xs font-semibold text-gray-800">Sehari Penuh</span>
                                    <span class="text-[10px] text-gray-500">Satu/beberapa hari</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rentang Tanggal (Sehari Penuh) -->
                        <div id="container-tanggal-range" class="{{ $oldTipe === 'sehari_penuh' ? '' : 'hidden' }} space-y-3 mb-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="input-tanggal-mulai" value="{{ old('tanggal_mulai') }}" class="w-full border rounded-lg p-3 text-sm" min="{{ now()->toDateString() }}">
                                    @error('tanggal_mulai')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="input-tanggal-selesai" value="{{ old('tanggal_selesai') }}" class="w-full border rounded-lg p-3 text-sm" min="{{ now()->toDateString() }}">
                                    @error('tanggal_selesai')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <p class="text-xs text-gray-400">Durasi: Sehari penuh (07:00 - 18:30) per hari. Maksimal 14 hari. Hari Minggu tutup.</p>
                        </div>

                        <div id="container-tanggal-biasa" class="{{ $oldTipe === 'sehari_penuh' ? 'hidden' : '' }} mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>

                            <div class="border rounded-lg p-3 mb-1">
                                <div class="flex items-center justify-between mb-2">
                                    <button type="button" id="cal-prev" class="px-2 py-1 rounded hover:bg-gray-100">&lsaquo;</button>
                                    <span id="cal-label" class="font-semibold text-sm"></span>
                                    <button type="button" id="cal-next" class="px-2 py-1 rounded hover:bg-gray-100">&rsaquo;</button>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-xs text-gray-400 mb-1">
                                    <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-sm" id="cal-grid"></div>

                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-2">
                                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span> Ada reservasi</span>
                                </div>

                                <div id="cal-booked-info" class="hidden bg-yellow-50 text-yellow-700 text-xs rounded-lg p-2 mt-2"></div>
                            </div>

                            <input type="hidden" name="tanggal" id="input-tanggal" value="{{ old('tanggal') }}">
                            @error('tanggal')
                                <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="container-jam-biasa" class="{{ $oldTipe === 'sehari_penuh' ? 'hidden' : '' }}">
                            <div class="grid grid-cols-2 gap-3 mt-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                    <input
                                        type="time"
                                        name="jam_mulai"
                                        id="input-jam-mulai"
                                        value="{{ old('jam_mulai') }}"
                                        min="07:00"
                                        max="18:30"
                                        class="w-full border rounded-lg p-3"
                                    >
                                    @error('jam_mulai')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                                    <input
                                        type="time"
                                        name="jam_selesai"
                                        id="input-jam-selesai"
                                        value="{{ old('jam_selesai') }}"
                                        min="07:00"
                                        max="18:30"
                                        class="w-full border rounded-lg p-3"
                                    >
                                    @error('jam_selesai')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <p class="text-gray-400 text-xs mt-2">Jam operasional gedung: 07.00 - 18.30</p>
                        </div>

                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Tujuan Reservasi <span id="tujuan-required-star" class="hidden text-red-500">*</span></label>
                        <div id="container-tujuan-select">
                            <select name="tujuan" id="input-tujuan" class="w-full border rounded-lg p-3 mb-1">
                                <option value="">Pilih tujuan reservasi</option>
                                <option value="Sidang" @selected(old('tujuan') === 'Sidang')>Sidang</option>
                                <option value="Rapat" @selected(old('tujuan') === 'Rapat')>Rapat</option>
                                <option value="Bimbingan" @selected(old('tujuan') === 'Bimbingan')>Bimbingan</option>
                                <option value="Ujian Sidang Tugas Akhir" @selected(old('tujuan') === 'Ujian Sidang Tugas Akhir')>Ujian Sidang Tugas Akhir</option>
                                <option value="Seminar" @selected(old('tujuan') === 'Seminar')>Seminar</option>
                                <option value="Lainnya" @selected(old('tujuan') === 'Lainnya')>Lainnya</option>
                            </select>
                        </div>
                        <div id="container-tujuan-input" class="hidden">
                            <input type="text" id="input-tujuan-input" value="{{ old('tujuan') }}" placeholder="Masukkan tujuan reservasi (wajib)" class="w-full border rounded-lg p-3 mb-1">
                        </div>
                        @error('tujuan')
                            <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                        @enderror

                        <div id="container-keterangan">
                            <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Keterangan <span id="keterangan-required-star" class="text-red-500">*</span></label>
                            <textarea
                                name="keterangan"
                                id="input-keterangan"
                                maxlength="200"
                                rows="3"
                                placeholder="Masukkan keterangan reservasi"
                                class="w-full border rounded-lg p-3"
                                required
                            >{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="notice-h2" class="hidden bg-blue-50 text-blue-700 text-sm rounded-lg p-3 mt-4">
                            Untuk ruangan lantai {{ $lantaiApproval }}, minimal reservasi H+{{ $minHariApproval }}.
                        </div>

                        @error('room_id')
                            <p class="text-red-500 text-xs mt-3">{{ $message }}</p>
                        @enderror

                        <button
                            type="button"
                            id="submit-btn"
                            onclick="openConfirmModal()"
                            class="w-full bg-blue-600 text-white rounded-lg py-3 mt-4 hover:bg-blue-700 transition font-semibold"
                        >
                            Kirim Reservasi
                        </button>
                    </form>
                </div>
            </div>

            {{-- Galeri foto + zoom --}}
            <div>
                <div class="relative bg-gray-900 rounded-xl overflow-hidden h-72 flex items-center justify-center">
                    <span class="absolute top-4 left-4 z-20 bg-black/70 text-white text-xs font-semibold px-3 py-1 rounded-full shadow" id="modal-photo-counter"></span>

                    <button type="button" id="modal-prev" class="absolute left-3 top-1/2 z-20 -translate-y-1/2 bg-white/90 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow text-xl font-semibold text-gray-700">&lsaquo;</button>

                    <div class="overflow-hidden w-full h-full flex items-center justify-center">
                        <img id="modal-photo" src="" class="max-h-full transition-transform duration-150 select-none" draggable="false">
                    </div>

                    <button type="button" id="modal-next" class="absolute right-3 top-1/2 z-20 -translate-y-1/2 bg-white/90 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow text-xl font-semibold text-gray-700">&rsaquo;</button>

                    <div class="absolute bottom-3 left-1/2 z-20 -translate-x-1/2 bg-black/60 rounded-full flex items-center gap-3 px-3 py-1">
                        <button type="button" id="modal-zoom-out" class="text-white px-1">&minus;</button>
                        <span class="text-white text-xs" id="modal-zoom-label">100%</span>
                        <button type="button" id="modal-zoom-in" class="text-white px-1">&plus;</button>
                    </div>
                </div>

                <div class="flex gap-2 mt-3 overflow-x-auto" id="modal-thumbnails"></div>
            </div>

        </div>

    </div>
</div>

{{-- ================= Modal Konfirmasi Reservasi ================= --}}
<div id="confirm-modal" class="hidden fixed inset-0 bg-black/60 z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl p-6 text-white">
            <div class="flex items-center gap-3 mb-1">
                <div class="bg-white/20 rounded-full p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold">Konfirmasi Reservasi</h3>
            </div>
            <p class="text-blue-100 text-sm">Periksa kembali detail reservasi Anda sebelum mengirim.</p>
        </div>

        {{-- Summary Content --}}
        <div class="p-6 space-y-3">

            {{-- Room name badge --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-0.5">Ruangan</p>
                <p class="font-bold text-gray-800 text-base" id="confirm-room-name"></p>
                <p class="text-sm text-gray-500" id="confirm-room-info"></p>
            </div>

            {{-- Details grid --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal</p>
                    <p class="font-semibold text-gray-800 text-sm" id="confirm-tanggal"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Waktu</p>
                    <p class="font-semibold text-gray-800 text-sm" id="confirm-waktu"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 col-span-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tujuan</p>
                    <p class="font-semibold text-gray-800 text-sm" id="confirm-tujuan"></p>
                </div>
            </div>

            {{-- Notes (conditional) --}}
            <div id="confirm-keterangan-row" class="bg-gray-50 rounded-xl p-3 hidden">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Catatan</p>
                <p class="text-gray-700 italic text-sm" id="confirm-keterangan"></p>
            </div>

            {{-- Warning if needs approval --}}
            <div id="confirm-approval-notice" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-3 flex items-start gap-2">
                <svg class="w-4 h-4 text-yellow-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-yellow-700 text-xs">Ruangan ini memerlukan persetujuan admin sebelum dapat digunakan.</p>
            </div>

        </div>

        {{-- Action Buttons --}}
        <div class="p-6 pt-0 flex gap-3">
            <button
                type="button"
                onclick="closeConfirmModal()"
                class="flex-1 border border-gray-300 text-gray-700 rounded-xl py-3 font-semibold hover:bg-gray-50 transition cursor-pointer"
            >
                Periksa Lagi
            </button>
            <button
                type="button"
                id="confirm-submit-btn"
                onclick="submitReservation()"
                class="flex-1 bg-blue-600 text-white rounded-xl py-3 font-semibold hover:bg-blue-700 transition cursor-pointer"
            >
                Ya, Kirim
            </button>
        </div>

    </div>
</div>

<script>
const lantaiApproval = @json((string) $lantaiApproval);
let currentSelectedRoom = null;
(function () {
    const floorTabs = document.querySelectorAll('.floor-tab');
    const floorPanels = document.querySelectorAll('.floor-panel');

    function showFloor(floor) {
        floorTabs.forEach(tab => {
            const active = tab.dataset.floor === floor;
            tab.classList.toggle('text-blue-600', active);
            tab.classList.toggle('border-b-2', active);
            tab.classList.toggle('border-blue-600', active);
            tab.classList.toggle('text-gray-500', !active);
        });
        floorPanels.forEach(panel => {
            panel.style.display = panel.dataset.floorPanel === floor ? '' : 'none';
        });
    }

    floorTabs.forEach(tab => {
        tab.addEventListener('click', () => showFloor(tab.dataset.floor));
    });

    if (floorTabs.length) {
        showFloor(floorTabs[0].dataset.floor);
    }

    // ---------- Modal & galeri ----------
    const modal = document.getElementById('room-modal');
    const modalPhoto = document.getElementById('modal-photo');
    const modalCounter = document.getElementById('modal-photo-counter');
    const modalThumbnails = document.getElementById('modal-thumbnails');
    const modalZoomLabel = document.getElementById('modal-zoom-label');

    let currentRoom = null;
    let currentPhotoIndex = 0;
    let currentZoom = 100;
    const placeholderPhoto = 'https://placehold.co/700x400?text=Belum+ada+foto';

    function renderPhoto() {
        const photos = (currentRoom.photos && currentRoom.photos.length) ? currentRoom.photos : [placeholderPhoto];
        modalPhoto.src = photos[currentPhotoIndex];
        modalPhoto.style.transform = 'scale(' + (currentZoom / 100) + ')';
        modalCounter.textContent = (currentPhotoIndex + 1) + ' / ' + photos.length;
        modalZoomLabel.textContent = currentZoom + '%';

        modalThumbnails.innerHTML = '';
        photos.forEach((url, i) => {
            const thumb = document.createElement('img');
            thumb.src = url;
            thumb.className = 'h-16 w-24 object-cover rounded-lg cursor-pointer border-2 ' + (i === currentPhotoIndex ? 'border-blue-600' : 'border-transparent');
            thumb.addEventListener('click', () => {
                currentPhotoIndex = i;
                currentZoom = 100;
                renderPhoto();
            });
            modalThumbnails.appendChild(thumb);
        });
    }

    function setupRoomDetails(room) {
        currentRoom = room;
        currentPhotoIndex = 0;
        currentZoom = 100;

        document.getElementById('modal-nama').textContent = room.nama;
        document.getElementById('modal-lantai-badge').textContent = 'Lantai ' + room.lantai;
        document.getElementById('modal-kapasitas').textContent = 'Kapasitas: ' + room.kapasitas + ' orang';
        document.getElementById('modal-jenis').textContent = room.jenis;
        document.getElementById('modal-lokasi').textContent = 'Gedung TULT - Lantai ' + room.lantai;
        document.getElementById('modal-deskripsi').textContent = room.deskripsi;

        const fasilitasEl = document.getElementById('modal-fasilitas');
        fasilitasEl.innerHTML = '';
        (room.fasilitas || []).forEach(item => {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2 text-gray-600';
            li.innerHTML = '<span class="text-blue-600">&#10003;</span> ' + item;
            fasilitasEl.appendChild(li);
        });

        const pilihBtn = document.getElementById('modal-pilih-ruangan');
        pilihBtn.disabled = room.status !== 'tersedia';
        pilihBtn.classList.toggle('bg-gray-300', room.status !== 'tersedia');
        pilihBtn.classList.toggle('cursor-not-allowed', room.status !== 'tersedia');
        pilihBtn.classList.toggle('bg-blue-600', room.status === 'tersedia');
        pilihBtn.classList.toggle('hover:bg-blue-700', room.status === 'tersedia');

        renderPhoto();
    }

    function openModal(room) {
        setupRoomDetails(room);

        document.getElementById('modal-title').textContent = 'Detail Ruangan';
        document.getElementById('modal-content-details').classList.remove('hidden');
        document.getElementById('modal-content-form').classList.add('hidden');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.getElementById('modal-back-to-details').addEventListener('click', () => {
        document.getElementById('modal-title').textContent = 'Detail Ruangan';
        document.getElementById('modal-content-details').classList.remove('hidden');
        document.getElementById('modal-content-form').classList.add('hidden');
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('modal-close').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.getElementById('modal-prev').addEventListener('click', () => {
        const total = (currentRoom.photos && currentRoom.photos.length) ? currentRoom.photos.length : 1;
        currentPhotoIndex = (currentPhotoIndex - 1 + total) % total;
        currentZoom = 100;
        renderPhoto();
    });

    document.getElementById('modal-next').addEventListener('click', () => {
        const total = (currentRoom.photos && currentRoom.photos.length) ? currentRoom.photos.length : 1;
        currentPhotoIndex = (currentPhotoIndex + 1) % total;
        currentZoom = 100;
        renderPhoto();
    });

    document.getElementById('modal-zoom-in').addEventListener('click', () => {
        currentZoom = Math.min(currentZoom + 20, 200);
        renderPhoto();
    });

    document.getElementById('modal-zoom-out').addEventListener('click', () => {
        currentZoom = Math.max(currentZoom - 20, 60);
        renderPhoto();
    });

    // ---------- Kalender custom ----------
    const calGrid = document.getElementById('cal-grid');
    const calLabel = document.getElementById('cal-label');
    const calBookedInfo = document.getElementById('cal-booked-info');
    const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    let calYear, calMonth, selectedDate = null;

    function toDateStr(y, m, d) {
        return y + '-' + String(m + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
    }

    function minAllowedDate(room) {
        const days = String(room.lantai) === lantaiApproval ? {{ $minHariApproval }} : 0;
        const d = new Date();
        d.setHours(0, 0, 0, 0);
        d.setDate(d.getDate() + days);
        return d;
    }

    function renderCalendar(room) {
        calGrid.innerHTML = '';
        calLabel.textContent = bulanNama[calMonth] + ' ' + calYear;

        const firstDay = new Date(calYear, calMonth, 1).getDay();
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        const minDate = minAllowedDate(room);
        const bookedDates = (room.booked || []).map(b => b.tanggal);

        for (let i = 0; i < firstDay; i++) {
            calGrid.appendChild(document.createElement('span'));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = toDateStr(calYear, calMonth, day);
            const cellDate = new Date(calYear, calMonth, day);
            const isSunday = cellDate.getDay() === 0;
            const disabled = cellDate < minDate || isSunday;
            const isBooked = bookedDates.includes(dateStr);
            const isSelected = dateStr === selectedDate;

            const cell = document.createElement('button');
            cell.type = 'button';
            cell.textContent = day;
            cell.className = 'py-2 rounded-lg relative ' +
                (disabled
                     ? 'text-gray-300 cursor-not-allowed'
                     : isSelected
                         ? 'bg-blue-600 text-white font-semibold'
                         : 'hover:bg-blue-50 text-gray-700');

            if (isBooked && !disabled) {
                const dot = document.createElement('span');
                dot.className = 'absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full ' + (isSelected ? 'bg-white' : 'bg-yellow-400');
                cell.appendChild(dot);
            }

            if (!disabled) {
                cell.addEventListener('click', () => {
                     selectedDate = dateStr;
                     document.getElementById('input-tanggal').value = dateStr;
                     renderCalendar(room);

                     const bookedToday = (room.booked || []).filter(b => b.tanggal === dateStr);
                     if (bookedToday.length) {
                         calBookedInfo.classList.remove('hidden');
                         calBookedInfo.innerHTML = 'Jam yang sudah dipesan: ' +
                             bookedToday.map(b => b.jam_mulai + '-' + b.jam_selesai).join(', ');
                     } else {
                         calBookedInfo.classList.add('hidden');
                     }
                });
            }

            calGrid.appendChild(cell);
        }
    }

    document.getElementById('cal-prev').addEventListener('click', () => {
        calMonth--;
        if (calMonth < 0) { calMonth = 11; calYear--; }
        renderCalendar(currentSelectedRoom);
    });

    document.getElementById('cal-next').addEventListener('click', () => {
        calMonth++;
        if (calMonth > 11) { calMonth = 0; calYear++; }
        renderCalendar(currentSelectedRoom);
    });

    // ---------- Tipe Reservasi Toggle ----------
    const radioBiasa = document.getElementById('radio-tipe-biasa');
    const radioSehari = document.getElementById('radio-tipe-sehari-penuh');
    const labelBiasa = document.getElementById('tipe-biasa-label');
    const labelSehari = document.getElementById('tipe-sehari-penuh-label');

    const containerBiasaTanggal = document.getElementById('container-tanggal-biasa');
    const containerBiasaJam = document.getElementById('container-jam-biasa');
    const containerRange = document.getElementById('container-tanggal-range');

    function updateTipeReservasiUI() {
        if (!radioBiasa || !radioSehari) return;
        if (radioBiasa.checked) {
            labelBiasa.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-blue-600 bg-blue-50/50";
            labelSehari.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-gray-200";
            
            containerBiasaTanggal.classList.remove('hidden');
            containerBiasaJam.classList.remove('hidden');
            containerRange.classList.add('hidden');
        } else {
            labelBiasa.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-gray-200";
            labelSehari.className = "border rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 border-blue-600 bg-blue-50/50";
            
            containerBiasaTanggal.classList.add('hidden');
            containerBiasaJam.classList.add('hidden');
            containerRange.classList.remove('hidden');
        }
    }

    if (radioBiasa && radioSehari) {
        radioBiasa.addEventListener('change', updateTipeReservasiUI);
        radioSehari.addEventListener('change', updateTipeReservasiUI);
        updateTipeReservasiUI();
    }

    function selectRoom(room) {
        setupRoomDetails(room);

        document.getElementById('input-room-id').value = room.id;
        document.getElementById('ringkasan-nama-ruangan').textContent = room.nama;
        document.getElementById('ringkasan-lantai-ruangan').innerHTML = 'Lantai ' + room.lantai + ' &middot; Kapasitas ' + room.kapasitas + ' orang';

        const notice = document.getElementById('notice-h2');
        notice.classList.toggle('hidden', String(room.lantai) !== lantaiApproval);

        const isFloor19 = String(room.lantai) === lantaiApproval;
        const containerSelect = document.getElementById('container-tujuan-select');
        const containerInput = document.getElementById('container-tujuan-input');
        const selectEl = document.getElementById('input-tujuan');
        const inputEl = document.getElementById('input-tujuan-input');
        const starEl = document.getElementById('tujuan-required-star');
        
        const containerKeterangan = document.getElementById('container-keterangan');
        const inputKeterangan = document.getElementById('input-keterangan');

        if (isFloor19) {
            containerSelect.classList.add('hidden');
            selectEl.removeAttribute('name');
            
            containerInput.classList.remove('hidden');
            inputEl.setAttribute('name', 'tujuan');
            starEl.classList.remove('hidden');
            
            containerKeterangan.classList.remove('hidden');
            inputKeterangan.setAttribute('name', 'keterangan');
            inputKeterangan.setAttribute('required', 'required');
            document.getElementById('keterangan-required-star').classList.remove('hidden');
        } else {
            containerInput.classList.add('hidden');
            inputEl.removeAttribute('name');
            starEl.classList.add('hidden');
            
            containerSelect.classList.remove('hidden');
            selectEl.setAttribute('name', 'tujuan');
            
            containerKeterangan.classList.remove('hidden');
            inputKeterangan.setAttribute('name', 'keterangan');
            inputKeterangan.setAttribute('required', 'required');
            document.getElementById('keterangan-required-star').classList.remove('hidden');
        }

        // Set min date range inputs
        const minDate = minAllowedDate(room);
        const minDateString = localDateString(minDate);
        const inputMulai = document.getElementById('input-tanggal-mulai');
        const inputSelesai = document.getElementById('input-tanggal-selesai');
        if (inputMulai) {
            inputMulai.min = minDateString;
            inputMulai.value = '';
        }
        if (inputSelesai) {
            inputSelesai.min = minDateString;
            inputSelesai.value = '';
        }

        currentSelectedRoom = room;
        const today = new Date();
        calYear = today.getFullYear();
        calMonth = today.getMonth();
        selectedDate = null;
        document.getElementById('input-tanggal').value = '';
        calBookedInfo.classList.add('hidden');
        renderCalendar(room);

        // Transition the modal content
        document.getElementById('modal-title').textContent = 'Reservasi Ruangan';
        document.getElementById('modal-content-details').classList.add('hidden');
        document.getElementById('modal-content-form').classList.remove('hidden');

        // Open modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.querySelectorAll('.btn-pilih-ruangan').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            const room = JSON.parse(this.closest('.room-card').dataset.room);
            openModal(room);
        });
    });

    document.getElementById('modal-pilih-ruangan').addEventListener('click', function () {
        if (this.disabled) return;
        selectRoom(currentRoom);
    });

    // ---------- Restore pilihan lama kalau validasi form gagal ----------
    const oldRoomId = @json(old('room_id'));
    if (oldRoomId) {
        const allCards = document.querySelectorAll('.room-card');
        for (const c of allCards) {
            const data = JSON.parse(c.dataset.room);
            if (String(data.id) === String(oldRoomId)) {
                selectRoom(data);
                showFloor(String(data.lantai));
                break;
            }
        }
    }
})();

// ====== Konfirmasi Modal ======
function localDateString(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return year + '-' + month + '-' + day;
}

function currentTimeString(date) {
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return hours + ':' + minutes;
}

function openConfirmModal() {
    const roomId    = document.getElementById('input-room-id').value;
    const tipeReservasi = document.querySelector('input[name="tipe_reservasi"]:checked').value;
    
    let tanggalText = '';
    let waktuText = '';
    
    const isFloor19 = currentSelectedRoom && String(currentSelectedRoom.lantai) === lantaiApproval;
    const tujuan    = isFloor19 
        ? document.getElementById('input-tujuan-input').value.trim() 
        : document.getElementById('input-tujuan').value;
    const keterangan= document.getElementById('input-keterangan').value.trim();
        
    const roomName  = document.getElementById('ringkasan-nama-ruangan').textContent;
    const roomInfo  = document.getElementById('ringkasan-lantai-ruangan').innerHTML;

    // Basic validation before showing modal
    if (!roomId) {
        alert('Silakan pilih ruangan terlebih dahulu.');
        return;
    }

    if (tipeReservasi === 'sehari_penuh') {
        const tanggalMulai = document.getElementById('input-tanggal-mulai').value;
        const tanggalSelesai = document.getElementById('input-tanggal-selesai').value;
        if (!tanggalMulai || !tanggalSelesai) {
            alert('Silakan isi tanggal mulai dan tanggal selesai.');
            return;
        }
        if (new Date(tanggalSelesai) < new Date(tanggalMulai)) {
            alert('Tanggal selesai tidak boleh sebelum tanggal mulai.');
            return;
        }
        
        // Sunday validation on frontend too!
        let tempDate = new Date(tanggalMulai + 'T00:00:00');
        let endDate = new Date(tanggalSelesai + 'T00:00:00');
        let totalDays = 0;
        let sundayCount = 0;
        while (tempDate <= endDate) {
            totalDays++;
            if (tempDate.getDay() === 0) {
                sundayCount++;
            }
            tempDate.setDate(tempDate.getDate() + 1);
        }
        if (sundayCount === totalDays) {
            alert('Pemesanan ditutup untuk hari Minggu. Silakan sesuaikan rentang tanggal Anda.');
            return;
        }

        const diffDays = Math.ceil((new Date(tanggalSelesai) - new Date(tanggalMulai)) / (1000 * 60 * 60 * 24)) + 1;
        if (diffDays > 14) {
            alert('Reservasi sehari penuh maksimal dapat dilakukan untuk 14 hari sekaligus.');
            return;
        }

        const tglM = new Date(tanggalMulai + 'T00:00:00');
        const tglS = new Date(tanggalSelesai + 'T00:00:00');
        const formattedMulai = tglM.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        const formattedSelesai = tglS.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        
        tanggalText = formattedMulai + ' s/d ' + formattedSelesai;
        waktuText = '07:00 – 18:30 (Sehari Penuh)';
    } else {
        const tanggal   = document.getElementById('input-tanggal').value;
        const jamMulai  = document.getElementById('input-jam-mulai').value;
        const jamSelesai= document.getElementById('input-jam-selesai').value;

        if (!tanggal) {
            alert('Silakan pilih tanggal reservasi.');
            return;
        }
        
        // Sunday validation on frontend
        const tglCheck = new Date(tanggal + 'T00:00:00');
        if (tglCheck.getDay() === 0) {
            alert('Pemesanan ditutup untuk hari Minggu.');
            return;
        }

        if (!jamMulai || !jamSelesai) {
            alert('Silakan isi jam mulai dan jam selesai.');
            return;
        }
        const now = new Date();
        if (tanggal === localDateString(now) && jamMulai <= currentTimeString(now)) {
            alert('Jam mulai reservasi sudah lewat. Untuk reservasi hari ini, pilih jam setelah waktu sekarang.');
            return;
        }

        const tgl = new Date(tanggal + 'T00:00:00');
        tanggalText = tgl.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        waktuText = jamMulai + ' – ' + jamSelesai;
    }

    if (!tujuan) {
        if (isFloor19) {
            alert('Silakan isi tujuan reservasi.');
        } else {
            alert('Silakan pilih tujuan reservasi.');
        }
        return;
    }

    if (!keterangan) {
        alert('Silakan isi keterangan reservasi.');
        return;
    }

    // Populate confirm modal
    document.getElementById('confirm-room-name').textContent = roomName;
    document.getElementById('confirm-room-info').innerHTML   = roomInfo;
    document.getElementById('confirm-tanggal').textContent = tanggalText;
    document.getElementById('confirm-waktu').textContent   = waktuText;
    document.getElementById('confirm-tujuan').textContent  = tujuan;

    const ketRow = document.getElementById('confirm-keterangan-row');
    if (keterangan) {
        document.getElementById('confirm-keterangan').textContent = keterangan;
        ketRow.classList.remove('hidden');
    } else {
        ketRow.classList.add('hidden');
    }

    // Approval notice
    const needsApproval = document.getElementById('notice-h2') &&
                          !document.getElementById('notice-h2').classList.contains('hidden');
    document.getElementById('confirm-approval-notice').classList.toggle('hidden', !needsApproval);

    // Show modal
    const modal = document.getElementById('confirm-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function submitReservation() {
    const btn = document.getElementById('confirm-submit-btn');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';
    btn.classList.add('opacity-75', 'cursor-not-allowed');
    document.getElementById('reservation-form').submit();
}

// Close modal on backdrop click
document.getElementById('confirm-modal').addEventListener('click', function (e) {
    if (e.target === this) closeConfirmModal();
});
</script>

@endsection
