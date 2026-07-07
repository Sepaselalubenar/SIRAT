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
        const days = String(room.lantai) === lantaiApproval ? {{ $minHariApproval }} : 1;
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
            const disabled = cellDate < minDate;
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

    let currentSelectedRoom = null;
    function selectRoom(room) {
        document.getElementById('ringkasan-kosong').classList.add('hidden');
        document.getElementById('reservation-form').classList.remove('hidden');

        document.getElementById('input-room-id').value = room.id;
        document.getElementById('ringkasan-nama-ruangan').textContent = room.nama;
        document.getElementById('ringkasan-lantai-ruangan').innerHTML = 'Lantai ' + room.lantai + ' &middot; Kapasitas ' + room.kapasitas + ' orang';

        const notice = document.getElementById('notice-h2');
        notice.classList.toggle('hidden', String(room.lantai) !== lantaiApproval);

        currentSelectedRoom = room;
        const today = new Date();
        calYear = today.getFullYear();
        calMonth = today.getMonth();
        selectedDate = document.getElementById('input-tanggal').value || null;
        calBookedInfo.classList.add('hidden');
        renderCalendar(room);

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