@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Ruangan</h1>
            <p class="text-gray-500 mt-1">Kelola data ruangan, fasilitas, dan foto ruangan untuk sistem reservasi.</p>
        </div>
        @if(!auth()->user()->isAdmin2())
            <button type="button" onclick="toggleModal('create', true)" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition-all duration-200">
                <span class="mr-2 text-lg font-bold">+</span> Tambah Ruangan
            </button>
        @endif
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

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <ul class="list-disc pl-5 space-y-1 text-sm text-red-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Grid Rooms -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($rooms as $room)
            <div class="room-card bg-white rounded-xl shadow hover:shadow-xl transition flex flex-col overflow-hidden">
                <!-- Cover Photo / Placeholder -->
                @if($room->photos->count() > 0)
                    <img src="{{ $room->photos->first()->url }}" class="h-44 w-full object-cover">
                @else
                    <div class="h-44 w-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                        Belum ada foto
                    </div>
                @endif

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <h2 class="font-bold text-lg text-gray-800">{{ $room->nama }}</h2>
                            @if($room->status === 'tersedia')
                                <span class="text-sm font-semibold text-green-600">Tersedia</span>
                            @elseif($room->status === 'dipakai')
                                <span class="text-sm font-semibold text-gray-500">Dipakai</span>
                            @else
                                <span class="text-sm font-semibold text-red-500">Maintenance</span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Lantai {{ $room->lantai }} &middot; {{ $room->jenis ?? 'Ruangan' }}</p>
                        <p class="text-gray-500 text-sm">Kapasitas: {{ $room->kapasitas }} orang</p>

                        @if($room->fasilitas && count($room->fasilitas) > 0)
                            <div class="flex flex-wrap gap-1.5 pt-2 mt-1">
                                @foreach($room->fasilitas as $fac)
                                    <span class="px-2.5 py-0.5 rounded text-xxs font-semibold bg-gray-100 text-gray-600 border border-gray-200">{{ $fac }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if($room->deskripsi)
                            <p class="text-xs text-gray-400 mt-3 line-clamp-2">{{ $room->deskripsi }}</p>
                        @endif
                    </div>

                    <!-- Action buttons -->
                    <div class="pt-4 border-t flex items-center justify-end gap-3 mt-5">
                        <button type="button" 
                                onclick="openReserveModal({{ json_encode($room) }})"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 border border-green-100 rounded-xl hover:bg-green-100 transition-colors duration-150 cursor-pointer">
                            Reservasi
                        </button>
                        <button type="button" 
                                onclick="openEditModal({{ json_encode($room->load('photos')) }})"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 transition-colors duration-150 cursor-pointer">
                            Edit
                        </button>
                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini? Semua foto juga akan dihapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 transition-colors duration-150 cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-150 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-4 text-lg font-bold text-gray-900">Belum ada ruangan</h3>
                @if(!auth()->user()->isAdmin2())
                    <p class="mt-2 text-sm text-gray-500">Silakan tambahkan ruangan baru dengan menekan tombol "+ Tambah Ruangan".</p>
                @endif
            </div>
        @endforelse
    </div>
</div>

<!-- Modals -->
@include('admin.rooms.partials.modal-create')
@include('admin.rooms.partials.modal-edit')
@include('admin.rooms.partials.modal-reserve')

<!-- Extra CSS for dynamic design helper -->
<style>
    .text-xxs {
        font-size: 0.65rem;
    }
</style>

<!-- Scripts -->
<script>
    // Global variable to keep track of edit room id
    let currentEditRoomId = null;

    function toggleModal(type, show) {
        const modal = document.getElementById(`modal-${type}`);
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function addFacilityInput(type, value = '') {
        const container = document.getElementById(`facilities-container-${type}`);
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" name="fasilitas[]" value="${value}" class="flex-1 rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Proyektor">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-lg px-2">❌</button>
        `;
        container.appendChild(div);
    }

    function openReserveModal(room) {
        document.getElementById('reserve-room-id').value = room.id;
        document.getElementById('reserve-room-name').textContent = room.nama + ' (Lantai ' + room.lantai + ')';
        toggleModal('reserve', true);
    }

    function openEditModal(room) {
        currentEditRoomId = room.id;

        // Set action form
        const form = document.getElementById('form-edit');
        form.action = `/admin/rooms/${room.id}`;

        // Populate basic values
        document.getElementById('edit-nama').value = room.nama || '';
        document.getElementById('edit-jenis').value = room.jenis || '';
        document.getElementById('edit-lantai').value = room.lantai || '';
        document.getElementById('edit-kapasitas').value = room.kapasitas || 1;
        document.getElementById('edit-status').value = room.status || 'tersedia';
        document.getElementById('edit-deskripsi').value = room.deskripsi || '';

        // Clear & populate facilities
        const container = document.getElementById('facilities-container-edit');
        container.innerHTML = '';
        if (room.fasilitas && room.fasilitas.length > 0) {
            room.fasilitas.forEach(fac => {
                addFacilityInput('edit', fac);
            });
        } else {
            addFacilityInput('edit');
        }

        // Populate photos list
        renderEditPhotos(room.photos || []);

        toggleModal('edit', true);
    }

    function renderEditPhotos(photos) {
        const container = document.getElementById('edit-photos-container');
        container.innerHTML = '';

        if (photos.length === 0) {
            container.innerHTML = '<p class="col-span-full text-sm text-gray-500 italic">Belum ada foto ruangan.</p>';
            return;
        }

        photos.forEach(photo => {
            const div = document.createElement('div');
            div.className = 'relative group rounded-xl overflow-hidden aspect-video border bg-gray-50';
            div.id = `photo-wrapper-${photo.id}`;
            div.innerHTML = `
                <img src="${photo.url}" class="w-full h-full object-cover">
                <button type="button" onclick="deleteRoomPhoto(${photo.id})" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white font-semibold text-xs transition duration-150">
                    <span class="bg-red-600 px-3 py-1.5 rounded-lg hover:bg-red-700">Hapus</span>
                </button>
            `;
            container.appendChild(div);
        });
    }

    async function uploadRoomPhoto() {
        if (!currentEditRoomId) return;

        const input = document.getElementById('edit-photo-input');
        if (!input.files || input.files.length === 0) return;

        const file = input.files[0];
        const formData = new FormData();
        formData.append('photo', file);

        const statusLabel = document.getElementById('upload-status');
        statusLabel.classList.remove('hidden');
        statusLabel.classList.add('inline-flex');

        try {
            const response = await axios.post(`/admin/rooms/${currentEditRoomId}/photos`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data.success) {
                // Fetch the new photo details
                const newPhoto = response.data.photo;
                
                // Add to list
                const container = document.getElementById('edit-photos-container');
                // Remove placeholder if present
                if (container.querySelector('p')) {
                    container.innerHTML = '';
                }

                const div = document.createElement('div');
                div.className = 'relative group rounded-xl overflow-hidden aspect-video border bg-gray-50';
                div.id = `photo-wrapper-${newPhoto.id}`;
                div.innerHTML = `
                    <img src="${newPhoto.url}" class="w-full h-full object-cover">
                    <button type="button" onclick="deleteRoomPhoto(${newPhoto.id})" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white font-semibold text-xs transition duration-150">
                        <span class="bg-red-600 px-3 py-1.5 rounded-lg hover:bg-red-700">Hapus</span>
                    </button>
                `;
                container.appendChild(div);

                alert('Foto berhasil diunggah!');
            }
        } catch (error) {
            console.error(error);
            alert(error.response?.data?.message || 'Gagal mengunggah foto. Pastikan ukuran file maks 10MB dan format gambar valid.');
        } finally {
            input.value = ''; // Reset input file
            statusLabel.classList.add('hidden');
            statusLabel.classList.remove('inline-flex');
        }
    }

    async function deleteRoomPhoto(photoId) {
        if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) return;

        try {
            const response = await axios.delete(`/admin/rooms/photos/${photoId}`);
            if (response.data.success) {
                const wrapper = document.getElementById(`photo-wrapper-${photoId}`);
                if (wrapper) {
                    wrapper.remove();
                }
                
                // Show empty text if no photos left
                const container = document.getElementById('edit-photos-container');
                if (container.children.length === 0) {
                    container.innerHTML = '<p class="col-span-full text-sm text-gray-500 italic">Belum ada foto ruangan.</p>';
                }
            }
        } catch (error) {
            console.error(error);
            alert('Gagal menghapus foto.');
        }
    }
</script>
@endsection
