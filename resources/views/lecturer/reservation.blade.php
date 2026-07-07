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

<div class="flex flex-col lg:flex-row gap-8 items-start">

    {{-- ================= Kolom kiri: tab lantai + daftar ruangan ================= --}}
    <div class="flex-1 w-full">

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
                            $butuhApproval = (string) $room->lantai === $lantaiApproval;
                            $statusLabel = match($room->status) {
                                'tersedia' => 'Tersedia',
                                'dipakai' => 'Dipakai',
                                'maintenance' => 'Maintenance',
                                default => ucfirst($room->status),
                            };
                            $statusColor = match($room->status) {
                                'tersedia' => 'text-green-600',
                                'dipakai' => 'text-gray-500',
                                'maintenance' => 'text-red-500',
                                default => 'text-gray-500',
                            };

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

                            <div class="p-5">
                                <h2 class="font-bold text-lg">{{ $room->nama }}</h2>
                                <p class="text-gray-500 text-sm mt-1">Lantai {{ $room->lantai }}</p>
                                <p class="text-gray-500 text-sm">Kapasitas: {{ $room->kapasitas }} orang</p>

                                <p class="mt-2 text-sm font-semibold {{ $statusColor }}">
                                    {{ $statusLabel }}
                                    @if($butuhApproval)
                                        <span class="ml-1 text-yellow-600 font-normal">&middot; Perlu Approval</span>
                                    @endif
                                </p>

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

    {{-- ================= Kolom kanan: Ringkasan Reservasi ================= --}}
    <aside class="w-full lg:w-96 shrink-0">
        <div class="bg-white rounded-xl shadow p-6 lg:sticky lg:top-6">

            <h3 class="text-xl font-bold text-gray-800">Ringkasan Reservasi</h3>

            <div id="ringkasan-kosong" class="text-center text-gray-400 py-10">
                <p class="text-4xl mb-2">&#128197;</p>
                <p>Belum ada ruangan dipilih</p>
            </div>

            <form method="POST" action="/reservation/store" id="reservation-form" class="hidden">
                @csrf

                <input type="hidden" name="room_id" id="input-room-id">

                <div id="ringkasan-terisi" class="bg-blue-50 rounded-xl p-4 mb-4">
                    <p class="font-semibold" id="ringkasan-nama-ruangan"></p>
                    <p class="text-gray-500 text-sm" id="ringkasan-lantai-ruangan"></p>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input
                    type="date"
                    name="tanggal"
                    id="input-tanggal"
                    value="{{ old('tanggal') }}"
                    class="w-full border rounded-lg p-3 mb-1"
                >
                @error('tanggal')
                    <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                @enderror

                <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Tujuan Reservasi</label>
                <select name="tujuan" id="input-tujuan" class="w-full border rounded-lg p-3 mb-1">
                    <option value="">Pilih tujuan reservasi</option>
                    <option value="Sidang" @selected(old('tujuan') === 'Sidang')>Sidang</option>
                    <option value="Meeting" @selected(old('tujuan') === 'Meeting')>Meeting</option>
                    <option value="Ujian Sidang Tugas Akhir" @selected(old('tujuan') === 'Ujian Sidang Tugas Akhir')>Ujian Sidang Tugas Akhir</option>
                    <option value="Seminar" @selected(old('tujuan') === 'Seminar')>Seminar</option>
                    <option value="Lainnya" @selected(old('tujuan') === 'Lainnya')>Lainnya</option>
                </select>
                @error('tujuan')
                    <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
                @enderror

                <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Keterangan (Opsional)</label>
                <textarea
                    name="keterangan"
                    id="input-keterangan"
                    maxlength="200"
                    rows="3"
                    placeholder="Tambahkan keterangan jika diperlukan"
                    class="w-full border rounded-lg p-3"
                >{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror

                <div id="notice-h2" class="hidden bg-blue-50 text-blue-700 text-sm rounded-lg p-3 mt-4">
                    Untuk ruangan lantai {{ $lantaiApproval }}, minimal reservasi H+{{ $minHariApproval }}.
                </div>

                @error('room_id')
                    <p class="text-red-500 text-xs mt-3">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    id="submit-btn"
                    class="w-full bg-blue-600 text-white rounded-lg py-3 mt-4 hover:bg-blue-700"
                >
                    Kirim Reservasi
                </button>
            </form>

        </div>
    </aside>

</div>

{{-- ================= Modal Detail Ruangan ================= --}}
<div id="room-modal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-bold">Detail Ruangan</h3>
            <button type="button" id="modal-close" class="text-2xl text-gray-400 hover:text-gray-700">&times;</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

            {{-- Info --}}
            <div>
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

            {{-- Galeri foto + zoom --}}
            <div>
                <div class="relative bg-gray-900 rounded-xl overflow-hidden h-72 flex items-center justify-center">
                    <span class="absolute top-3 left-3 bg-black/60 text-white text-xs px-2 py-1 rounded" id="modal-photo-counter"></span>

                    <button type="button" id="modal-prev" class="absolute left-3 bg-white/90 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center">&lsaquo;</button>

                    <div class="overflow-hidden w-full h-full flex items-center justify-center">
                        <img id="modal-photo" src="" class="max-h-full transition-transform duration-150 select-none" draggable="false">
                    </div>

                    <button type="button" id="modal-next" class="absolute right-3 bg-white/90 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center">&rsaquo;</button>

                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/60 rounded-full flex items-center gap-3 px-3 py-1">
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

<script>
(function () {
    const floorTabs = document.querySelectorAll('.floor-tab');
    const floorPanels = document.querySelectorAll('.floor-panel');
    const lantaiApproval = @json((string) $lantaiApproval);

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

    function openModal(room) {
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

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

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

    // ---------- Pilih ruangan (dari card ATAU dari modal) ----------
    function selectRoom(room) {
        document.getElementById('ringkasan-kosong').classList.add('hidden');
        document.getElementById('reservation-form').classList.remove('hidden');

        document.getElementById('input-room-id').value = room.id;
        document.getElementById('ringkasan-nama-ruangan').textContent = room.nama;
        document.getElementById('ringkasan-lantai-ruangan').innerHTML = 'Lantai ' + room.lantai + ' &middot; Kapasitas ' + room.kapasitas + ' orang';

        const tanggalInput = document.getElementById('input-tanggal');
        const notice = document.getElementById('notice-h2');
        const today = new Date();
        const minDays = String(room.lantai) === lantaiApproval ? {{ $minHariApproval }} : 1;
        const minDate = new Date(today);
        minDate.setDate(today.getDate() + minDays);
        tanggalInput.min = minDate.toISOString().slice(0, 10);

        notice.classList.toggle('hidden', String(room.lantai) !== lantaiApproval);

        closeModal();
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
</script>

@endsection