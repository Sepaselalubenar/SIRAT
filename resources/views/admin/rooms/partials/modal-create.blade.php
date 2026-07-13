<!-- Modal Create Room -->
<div id="modal-create" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Tambah Ruangan Baru</h3>
            <button type="button" onclick="toggleModal('create', false)" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
        </div>

        <form action="{{ route('admin.rooms.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ruangan *</label>
                    <input type="text" name="nama" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: R. Sidang Lantai 19">
                </div>

                <!-- Jenis Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Ruangan</label>
                    <input type="text" name="jenis" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Ruang Sidang, Kelas, Lab">
                </div>

                <!-- Lantai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lantai *</label>
                    @if(auth()->user()->admin_type === 2)
                        <input type="text" name="lantai" value="19" readonly required class="w-full rounded-xl border border-gray-300 bg-gray-100 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-not-allowed">
                        <span class="text-xs text-gray-400 mt-1 block">Terkunci: Admin 2 hanya dapat mengelola lantai 19.</span>
                    @else
                        <input type="text" name="lantai" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: 3 (Selain 19)">
                        <span class="text-xs text-gray-400 mt-1 block">Admin 1 dapat mengelola semua lantai kecuali 19.</span>
                    @endif
                </div>

                <!-- Kapasitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas (Orang) *</label>
                    <input type="number" name="kapasitas" required min="1" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: 30">
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Ruangan *</label>
                    <select name="status" required class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipakai">Dipakai</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Ruangan</label>
                <textarea name="deskripsi" rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Deskripsi detail mengenai ruangan..."></textarea>
            </div>

            <!-- Fasilitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Ruangan</label>
                <div id="facilities-container-create" class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="text" name="fasilitas[]" class="flex-1 rounded-xl border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Contoh: Proyektor">
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-lg px-2">❌</button>
                    </div>
                </div>
                <button type="button" onclick="addFacilityInput('create')" class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold gap-1">
                    <span class="text-base">+</span> Tambah Fasilitas
                </button>
            </div>

            <div class="pt-6 border-t flex justify-end gap-3">
                <button type="button" onclick="toggleModal('create', false)" class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">
                    Simpan Ruangan
                </button>
            </div>
        </form>
    </div>
</div>
