<!-- Modal Edit Room -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Edit Data Ruangan</h3>
            <button type="button" onclick="toggleModal('edit', false)" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Form Edit Details -->
            <form id="form-edit" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Ruangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ruangan *</label>
                        <input type="text" name="nama" id="edit-nama" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Jenis Ruangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Ruangan</label>
                        <input type="text" name="jenis" id="edit-jenis" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Lantai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lantai *</label>
                        <input type="text" name="lantai" id="edit-lantai" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas (Orang) *</label>
                        <input type="number" name="kapasitas" id="edit-kapasitas" required min="1" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Ruangan *</label>
                        <select name="status" id="edit-status" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="tersedia">Tersedia</option>
                            <option value="dipakai">Dipakai</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Ruangan</label>
                    <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <!-- Fasilitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Ruangan</label>
                    <div id="facilities-container-edit" class="space-y-3">
                        <!-- Populated by Javascript -->
                    </div>
                    <button type="button" onclick="addFacilityInput('edit')" class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold gap-1">
                        <span class="text-base">+</span> Tambah Fasilitas
                    </button>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('edit', false)" class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition text-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <!-- Foto Ruangan (Upload & Delete Section) -->
            <div class="border-t pt-6">
                <label class="block text-sm font-medium text-gray-700 mb-3 font-semibold">Galeri Foto Ruangan</label>
                
                <!-- Photos list -->
                <div id="edit-photos-container" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    <!-- Populated dynamically via JS -->
                </div>

                <!-- Photo Upload Dropzone/Btn -->
                <div class="flex items-center gap-4">
                    <label class="cursor-pointer inline-flex items-center px-4 py-2.5 rounded-xl border border-blue-600 bg-blue-55 text-blue-700 hover:bg-blue-100 font-semibold text-sm transition">
                        <span>+ Unggah Foto Baru</span>
                        <input type="file" id="edit-photo-input" class="hidden" accept="image/*" onchange="uploadRoomPhoto()">
                    </label>
                    <span id="upload-status" class="text-sm text-gray-500 hidden items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengunggah...
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
