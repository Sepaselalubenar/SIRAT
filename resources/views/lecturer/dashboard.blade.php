@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <div>
        <!-- <h1 class="text-3xl font-bold text-gray-800">
            Dashboard
        </h1> -->

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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b py-4 last:border-b-0 gap-4">
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
                    @elseif($reservation->status === 'cancelled' && $reservation->alasan_pembatalan)
                        <p class="text-xs text-amber-600 mt-1 italic">Alasan pembatalan: "{{ $reservation->alasan_pembatalan }}"</p>
                    @endif
                </div>

                <div>
                    @if($reservation->status === 'approved')
                        <span class="text-green-600 font-semibold text-sm">Disetujui</span>
                    @elseif($reservation->status === 'pending')
                        <span class="text-yellow-500 font-semibold text-sm">Menunggu Approval</span>
                    @elseif($reservation->status === 'cancelled')
                        <span class="text-amber-600 font-semibold text-sm">Dibatalkan</span>
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

    {{-- ================= Kalender Ketersediaan Ruangan ================= --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Kalender Ketersediaan Ruangan</h2>
            <p class="text-gray-500 text-sm mt-1">Cek jadwal pemakaian setiap ruangan secara real-time.</p>
        </div>

        <!-- Filters -->
        <div class="bg-gray-50 rounded-xl p-5 mb-6 border border-gray-150">
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                <!-- Room Filter -->
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Ruangan</label>
                    <select id="room-filter" class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="all">Semua Ruangan</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->nama }} (Lantai {{ $room->lantai }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Picker -->
                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                    <input type="date" id="date-filter" value="{{ now()->toDateString() }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                </div>

                <!-- Nav Buttons -->
                <div class="flex items-center gap-2 shrink-0">
                    <button id="prev-day" class="p-3 rounded-xl border border-gray-200 hover:bg-gray-100 transition text-gray-600 cursor-pointer" title="Hari sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="today-btn" class="px-4 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition cursor-pointer">
                        Hari Ini
                    </button>
                    <button id="next-day" class="p-3 rounded-xl border border-gray-200 hover:bg-gray-100 transition text-gray-600 cursor-pointer" title="Hari berikutnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Legend, Display Date & View Switcher -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div id="date-display" class="text-lg font-bold text-gray-800"></div>
                    <div id="loading-spinner" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>

                <!-- View Toggle Switcher -->
                <div class="flex bg-gray-100 p-1 rounded-xl shadow-inner border border-gray-200 self-start sm:self-auto">
                    <button id="view-timeline-btn" class="px-3 py-1 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M3 14h18m-9-4v8m-9-8v8m18-8v8" />
                        </svg>
                        Timeline
                    </button>
                    <button id="view-list-btn" class="px-3 py-1 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Daftar Agenda
                    </button>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap gap-4 text-xs font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                    <span class="text-gray-600">Tersedia</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                    <span class="text-gray-600">Disetujui</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
                    <span class="text-gray-600">Selesai</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span>
                    <span class="text-gray-600">Menunggu Persetujuan</span>
                </div>
            </div>
        </div>

        <!-- Timeline Grid & List View -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div id="calendar-container" class="overflow-x-auto">
                <div id="calendar-grid" class="min-w-[700px]">
                    <!-- Renders dynamically via JS -->
                </div>
            </div>

            <div id="list-container" class="hidden divide-y divide-gray-100">
                <!-- Renders dynamically via JS -->
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden py-16 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-400 font-medium">Tidak ada reservasi pada tanggal ini.</p>
                <p class="text-gray-300 text-sm mt-1">Semua ruangan tersedia!</p>
            </div>
        </div>
    </div>

</div>

<!-- Reservation Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Detail Reservasi</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold cursor-pointer">&times;</button>
        </div>
        <div id="detail-content" class="p-6 space-y-3 text-sm">
            <!-- Filled dynamically -->
        </div>
    </div>
</div>

<script>
    // ====== Config ======
    const TIME_START = 7;      // 07:00
    const TIME_END   = 19;     // 19:00
    const TOTAL_HOURS = TIME_END - TIME_START;

    let currentDate = '{{ now()->toDateString() }}';
    let allRooms    = @json($rooms);

    const roomFilter   = document.getElementById('room-filter');
    const dateFilter   = document.getElementById('date-filter');
    const grid         = document.getElementById('calendar-grid');
    const emptyState   = document.getElementById('empty-state');
    const dateDisplay  = document.getElementById('date-display');
    const spinner      = document.getElementById('loading-spinner');
    const auth_role    = '{{ auth()->user()->role }}';

    const calendarContainer = document.getElementById('calendar-container');
    const listContainer     = document.getElementById('list-container');
    const viewTimelineBtn   = document.getElementById('view-timeline-btn');
    const viewListBtn       = document.getElementById('view-list-btn');

    let currentView = window.innerWidth < 768 ? 'list' : 'timeline';
    let lastReservationsData = [];

    // ====== Formatting Helpers ======
    function formatDateDisplay(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }

    function formatDate(d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function timeToPercent(timeStr) {
        const [h, m] = timeStr.split(':').map(Number);
        const minutes = (h - TIME_START) * 60 + m;
        return (minutes / (TOTAL_HOURS * 60)) * 100;
    }

    function durationPercent(startStr, endStr) {
        const [sh, sm] = startStr.split(':').map(Number);
        const [eh, em] = endStr.split(':').map(Number);
        const startMin = (sh - TIME_START) * 60 + sm;
        const endMin   = (eh - TIME_START) * 60 + em;
        return ((endMin - startMin) / (TOTAL_HOURS * 60)) * 100;
    }

    // ====== Helper for Room Intervals ======
    function getRoomIntervals(roomRes) {
        const sorted = [...roomRes].sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));
        const intervals = [];
        
        const pad = (n) => String(n).padStart(2, '0');
        const timeToMinutes = (t) => {
            const [h, m] = t.split(':').map(Number);
            return h * 60 + m;
        };
        const minutesToTime = (m) => {
            const h = Math.floor(m / 60);
            const mins = m % 60;
            return `${pad(h)}:${pad(mins)}`;
        };

        let currentTimeMins = TIME_START * 60;
        const endTimeMins = TIME_END * 60;

        sorted.forEach(res => {
            const resStartMins = timeToMinutes(res.jam_mulai);
            const resEndMins = timeToMinutes(res.jam_selesai);

            if (resStartMins > currentTimeMins) {
                intervals.push({
                    start: minutesToTime(currentTimeMins),
                    end: minutesToTime(resStartMins),
                    type: 'free'
                });
            }

            intervals.push({
                start: res.jam_mulai,
                end: res.jam_selesai,
                type: 'booked',
                reservation: res
            });

            currentTimeMins = Math.max(currentTimeMins, resEndMins);
        });

        if (currentTimeMins < endTimeMins) {
            intervals.push({
                start: minutesToTime(currentTimeMins),
                end: minutesToTime(endTimeMins),
                type: 'free'
            });
        }

        return intervals;
    }

    // ====== Switch View UI Update ======
    function updateToggleUI() {
        if (currentView === 'timeline') {
            viewTimelineBtn.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition cursor-pointer bg-white text-blue-600 shadow-sm border border-gray-200';
            viewListBtn.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer text-gray-500 hover:text-gray-800 border border-transparent';
            calendarContainer.classList.remove('hidden');
            listContainer.classList.add('hidden');
        } else {
            viewTimelineBtn.className = 'px-3.5 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer text-gray-500 hover:text-gray-800 border border-transparent';
            viewListBtn.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition cursor-pointer bg-white text-blue-600 shadow-sm border border-gray-200';
            calendarContainer.classList.add('hidden');
            listContainer.classList.remove('hidden');
        }
    }

    // ====== General Render Router ======
    function render() {
        dateDisplay.textContent = formatDateDisplay(currentDate);
        updateToggleUI();

        const selectedRoomId = roomFilter.value;
        let roomsToShow = allRooms;
        if (selectedRoomId !== 'all') {
            roomsToShow = allRooms.filter(r => r.id == selectedRoomId);
        }

        if (roomsToShow.length === 0) {
            emptyState.classList.remove('hidden');
            calendarContainer.classList.add('hidden');
            listContainer.classList.add('hidden');
            return;
        }
        emptyState.classList.add('hidden');

        if (currentView === 'timeline') {
            renderTimeline(roomsToShow, lastReservationsData);
        } else {
            renderList(roomsToShow, lastReservationsData);
        }
    }

    function isResPast(res) {
        if (res.is_past !== undefined) return res.is_past;
        const jamSelesai = res.jam_selesai.length === 5 ? res.jam_selesai + ':00' : res.jam_selesai;
        return new Date(res.tanggal + 'T' + jamSelesai) < new Date();
    }

    // ====== Render Timeline ======
    function renderTimeline(roomsToShow, reservations) {
        const todayDateStr = '{{ now()->toDateString() }}';
        const isPast = currentDate < todayDateStr;
        const isSunday = new Date(currentDate + 'T00:00:00').getDay() === 0;
        grid.innerHTML = '';
        spinner.classList.add('hidden');

        // Build a map: room_id -> reservations
        const resMap = {};
        reservations.forEach(res => {
            if (!resMap[res.room_id]) resMap[res.room_id] = [];
            resMap[res.room_id].push(res);
        });

        // Hour labels header
        const header = document.createElement('div');
        header.className = 'flex border-b border-gray-100 bg-gray-50';
        header.innerHTML = `
            <div class="w-44 shrink-0 p-3 text-xs font-semibold text-gray-400 uppercase tracking-wider border-r border-gray-100">Ruangan</div>
            <div class="flex-1 relative">
                <div class="flex">
                    ${Array.from({length: TOTAL_HOURS + 1}, (_, i) => {
                        const h = TIME_START + i;
                        return `<div class="flex-1 text-xs text-gray-400 font-medium text-center py-3 border-r border-gray-100 last:border-r-0">${String(h).padStart(2,'0')}:00</div>`;
                    }).join('')}
                </div>
            </div>
        `;
        grid.appendChild(header);

        roomsToShow.forEach(room => {
            const row = document.createElement('div');
            row.className = 'flex border-b border-gray-100 last:border-b-0 hover:bg-gray-50/50 transition-colors items-center';

            const roomLabel = document.createElement('div');
            roomLabel.className = 'w-44 shrink-0 p-4 border-r border-gray-100 self-stretch flex flex-col justify-center';
            roomLabel.innerHTML = `
                <div class="font-semibold text-gray-800 text-sm leading-tight">${room.nama}</div>
                <div class="text-xs text-gray-400 mt-0.5">Lantai ${room.lantai}</div>
            `;

            // Sort reservations by start time
            const roomRes = [...(resMap[room.id] || [])].sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));

            // Distribute reservations into tracks
            const tracks = [];
            roomRes.forEach(res => {
                let placed = false;
                for (let i = 0; i < tracks.length; i++) {
                    const lastRes = tracks[i][tracks[i].length - 1];
                    if (res.jam_mulai >= lastRes.jam_selesai) {
                        tracks[i].push(res);
                        placed = true;
                        res._trackIndex = i;
                        break;
                    }
                }
                if (!placed) {
                    tracks.push([res]);
                    res._trackIndex = tracks.length - 1;
                }
            });

            const totalTracks = Math.max(tracks.length, 1);
            const TRACK_HEIGHT = 38;

            const trackWrapper = document.createElement('div');
            trackWrapper.className = 'flex-1 relative px-1';
            trackWrapper.style.height = `${totalTracks * TRACK_HEIGHT + 8}px`;

            // Background grid lines per hour
            const bgGrid = document.createElement('div');
            bgGrid.className = 'absolute inset-0 flex pointer-events-none';
            for (let i = 0; i <= TOTAL_HOURS; i++) {
                const line = document.createElement('div');
                line.className = 'flex-1 border-r border-gray-100 last:border-r-0';
                bgGrid.appendChild(line);
            }
            trackWrapper.appendChild(bgGrid);

            // Reservation blocks
            roomRes.forEach(res => {
                const left = timeToPercent(res.jam_mulai);
                const width = durationPercent(res.jam_mulai, res.jam_selesai);
                const isApproved = res.status === 'approved';
                const isPastRes = isResPast(res);
                const colorClass = isApproved
                    ? (isPastRes ? 'bg-gray-400 hover:bg-gray-500' : 'bg-blue-500 hover:bg-blue-600')
                    : 'bg-yellow-400 hover:bg-yellow-500';

                const block = document.createElement('button');
                block.className = `absolute ${colorClass} rounded-lg text-white text-xs font-semibold flex items-center px-2 overflow-hidden transition-all shadow-sm cursor-pointer`;
                block.style.top = `${res._trackIndex * TRACK_HEIGHT + 4}px`;
                block.style.height = `${TRACK_HEIGHT - 6}px`;
                block.style.left  = `${left}%`;
                block.style.width = `${Math.max(width, 2)}%`;
                block.innerHTML   = `<span class="truncate">${res.jam_mulai}-${res.jam_selesai}</span>`;
                block.title       = `${res.room_name}: ${res.jam_mulai}-${res.jam_selesai} | ${res.user_name} | ${res.tujuan}`;
                block.addEventListener('click', () => openDetailModal(res));
                trackWrapper.appendChild(block);
            });

            // "Tersedia" label if no reservations
            if (roomRes.length === 0) {
                const available = document.createElement('div');
                available.className = 'absolute inset-y-0 left-1 right-1 flex items-center px-3';
                if (isSunday) {
                    available.innerHTML = `<span class="text-xs text-amber-700 font-semibold bg-amber-50 rounded-lg px-3 py-1 border border-amber-200">✗ Tutup (Hari Minggu)</span>`;
                } else if (isPast) {
                    available.innerHTML = `<span class="text-xs text-gray-500 font-semibold bg-gray-100 rounded-lg px-3 py-1 border border-gray-200">Reservasi ditutup</span>`;
                } else {
                    available.innerHTML = `<span class="text-xs text-green-600 font-semibold bg-green-50 rounded-lg px-3 py-1 border border-green-200">✓ Tersedia seharian</span>`;
                }
                trackWrapper.appendChild(available);
            }

            row.appendChild(roomLabel);
            row.appendChild(trackWrapper);
            grid.appendChild(row);
        });
    }

    // ====== Render List / Agenda ======
    function renderList(roomsToShow, reservations) {
        const todayDateStr = '{{ now()->toDateString() }}';
        const isPast = currentDate < todayDateStr;
        const isSunday = new Date(currentDate + 'T00:00:00').getDay() === 0;
        listContainer.innerHTML = '';
        spinner.classList.add('hidden');

        // Build a map: room_id -> reservations
        const resMap = {};
        reservations.forEach(res => {
            if (!resMap[res.room_id]) resMap[res.room_id] = [];
            resMap[res.room_id].push(res);
        });

        roomsToShow.forEach(room => {
            const roomRes = resMap[room.id] || [];
            
            // Outer Room Card
            const roomCard = document.createElement('div');
            
            // If the room is completely available, make it compact
            if (roomRes.length === 0) {
                roomCard.className = 'p-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors';
                let badge;
                if (isSunday) {
                    badge = `<span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-lg border border-amber-200 shrink-0">✗ Tutup (Hari Minggu)</span>`;
                } else if (isPast) {
                    badge = `<span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-lg border border-gray-200 shrink-0">Reservasi ditutup</span>`;
                } else {
                    badge = `<span class="px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-lg border border-green-200 shrink-0">✓ Tersedia seharian</span>`;
                }
                
                roomCard.innerHTML = `
                    <div>
                        <div class="font-bold text-gray-800 text-sm leading-tight">${room.nama}</div>
                        <div class="text-[10px] text-gray-400 mt-0.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Lantai ${room.lantai}
                        </div>
                    </div>
                    ${badge}
                `;
            } else {
                // Room has bookings
                roomCard.className = 'p-5 flex flex-col gap-3 hover:bg-gray-50/50 transition-colors';
                
                // Room header inside card
                const headerDiv = document.createElement('div');
                headerDiv.className = 'flex items-center justify-between pb-2 border-b border-gray-150/60';
                headerDiv.innerHTML = `
                    <div>
                        <div class="font-bold text-gray-800 text-sm leading-tight">${room.nama}</div>
                        <div class="text-[10px] text-gray-400 mt-0.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Lantai ${room.lantai}
                        </div>
                    </div>
                    <span class="text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md font-semibold border border-blue-100 shrink-0">
                        ${roomRes.length} Reservasi
                    </span>
                `;
                roomCard.appendChild(headerDiv);

                // Reservations list
                const agendaWrapper = document.createElement('div');
                agendaWrapper.className = 'space-y-2';

                // Sort reservations by start time
                const sortedRes = [...roomRes].sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));

                sortedRes.forEach(res => {
                    const isApproved = res.status === 'approved';
                    const isPastRes = isResPast(res);
                    const dotColor = isApproved ? (isPastRes ? 'bg-gray-400' : 'bg-blue-500') : 'bg-yellow-400';
                    const bgClass = isApproved
                        ? (isPastRes ? 'bg-gray-50/60 hover:bg-gray-100/80 border-gray-200' : 'bg-blue-50/40 hover:bg-blue-50/80 border-blue-200')
                        : 'bg-yellow-50/40 hover:bg-yellow-50/80 border-yellow-200';
                    const badgeColor = isApproved
                        ? (isPastRes ? 'bg-gray-100 text-gray-600 border-gray-200' : 'bg-blue-100 text-blue-700 border-blue-200')
                        : 'bg-yellow-100 text-yellow-700 border-yellow-200';
                    const badgeText = isApproved ? (isPastRes ? 'Selesai' : 'Disetujui') : 'Menunggu';

                    const block = document.createElement('div');
                    block.className = `flex flex-col gap-2 p-3 border rounded-xl shadow-sm transition cursor-pointer ${bgClass}`;
                    block.innerHTML = `
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full ${dotColor} shrink-0"></div>
                                <span class="text-xs font-bold text-gray-800">${res.jam_mulai} – ${res.jam_selesai}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border ${badgeColor}">${badgeText}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-600 pt-1 border-t border-gray-100/50">
                            <div><span class="text-gray-400">Pemesan:</span> <span class="font-medium text-gray-700">${res.user_name}</span></div>
                            <div><span class="text-gray-400">Tujuan:</span> <span class="font-medium text-gray-700">${res.tujuan}</span></div>
                        </div>
                    `;
                    block.addEventListener('click', () => openDetailModal(res));
                    agendaWrapper.appendChild(block);
                });

                roomCard.appendChild(agendaWrapper);
            }

            listContainer.appendChild(roomCard);
        });
    }

    // ====== Fetch Data ======
    function fetchCalendar() {
        spinner.classList.remove('hidden');
        grid.innerHTML = '';
        listContainer.innerHTML = '';
        emptyState.classList.add('hidden');

        const roomId = roomFilter.value;
        const eventsBase = auth_role === 'admin' ? '/admin/calendar/events' : '/calendar/events';
        const url = `${eventsBase}?date=${currentDate}&room_id=${roomId}`;

        fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            lastReservationsData = data;
            render();
        })
        .catch(() => {
            spinner.classList.add('hidden');
            grid.innerHTML = '<div class="p-8 text-center text-gray-400">Gagal memuat data. Coba muat ulang halaman.</div>';
            listContainer.innerHTML = '<div class="p-8 text-center text-gray-400">Gagal memuat data. Coba muat ulang halaman.</div>';
        });
    }

    // ====== Detail Modal ======
    function openDetailModal(res) {
        const isPastRes = res.is_past !== undefined
            ? res.is_past
            : (new Date(res.tanggal + 'T' + (res.jam_selesai.length === 5 ? res.jam_selesai + ':00' : res.jam_selesai)) < new Date());

        const statusLabel = res.status === 'approved'
            ? (isPastRes
                ? '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Selesai</span>'
                : '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Disetujui</span>')
            : '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">Menunggu Persetujuan</span>';

        document.getElementById('detail-content').innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-gray-800 text-base">${res.room_name}</p>
                    <p class="text-gray-400 text-xs mt-0.5">Lantai ${res.room_lantai}</p>
                </div>
                ${statusLabel}
            </div>
            <hr class="border-gray-100">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal</p>
                    <p class="font-medium text-gray-800">${new Date(res.tanggal + 'T00:00:00').toLocaleDateString('id-ID', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'})}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Waktu</p>
                    <p class="font-medium text-gray-800">${res.jam_mulai} – ${res.jam_selesai}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Pemesan</p>
                    <p class="font-medium text-gray-800">${res.user_name}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tujuan</p>
                    <p class="font-medium text-gray-800">${res.tujuan}</p>
                </div>
            </div>
            ${res.keterangan ? `
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Catatan</p>
                <p class="text-gray-700 italic">"${res.keterangan}"</p>
            </div>` : ''}
        `;
        document.getElementById('detail-modal').classList.remove('hidden');
    }

    // ====== Detail Modal Close ======
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    // ====== Events ======
    dateFilter.addEventListener('change', () => {
        currentDate = dateFilter.value;
        fetchCalendar();
    });

    roomFilter.addEventListener('change', () => {
        fetchCalendar();
    });

    document.getElementById('today-btn').addEventListener('click', () => {
        const today = formatDate(new Date());
        currentDate = today;
        dateFilter.value = today;
        fetchCalendar();
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const d = new Date(currentDate + 'T00:00:00');
        d.setDate(d.getDate() - 1);
        currentDate = formatDate(d);
        dateFilter.value = currentDate;
        fetchCalendar();
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const d = new Date(currentDate + 'T00:00:00');
        d.setDate(d.getDate() + 1);
        currentDate = formatDate(d);
        dateFilter.value = currentDate;
        fetchCalendar();
    });

    document.getElementById('detail-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    viewTimelineBtn.addEventListener('click', () => {
        currentView = 'timeline';
        render();
    });

    viewListBtn.addEventListener('click', () => {
        currentView = 'list';
        render();
    });

    // ====== Initial Load ======
    fetchCalendar();
</script>
@endsection